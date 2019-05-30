<?php

namespace Tests\Feature\GraphQL;

use App\User;
use App\Workspace;
use Tests\TestCase;
use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateChannelTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

    private function validParams(array $overrides = [])
    {
        $mutation = <<<'EOL'
mutation channels($workspace_id: Int, $name: String) {
    createChannel(workspace_id: $workspace_id, name: $name) {
        id
        name
    }
}
EOL;

        return array_replace_recursive([
            'query' => $mutation,
            'variables' => [
                'name' => $this->faker->word,
                'workspace_id' => null,
            ],
        ], $overrides);
    }

    public function testMustBeAuthenticated()
    {
        $this->postJson('/graphql', $this->validParams())
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function testValidationFails()
    {
        $user = factory(User:: class)->create();

        $response = $this->actingAs($user, 'api')
            ->postJson('/graphql', $this->validParams([
                'variables' => [
                    'name' => null,
                    'workspace_id' => null,
                ],
            ]));

        $response->assertOk();
        $response->assertJsonStructure([
            'errors' => [
                '*' => [
                    'validation' => [
                        'name',
                        'workspace_id',
                    ],
                ],
            ],
        ]);
    }

    public function testCannotCreateChannelsInWorkspacesTheUserDontBelong()
    {
        $user = factory(User:: class)->create();

        $response = $this->actingAs($user, 'api')
            ->postJson('/graphql', $this->validParams([
                'variables' => [
                    'workspace_id' => factory(Workspace::class)->create()->getKey(),
                ],
            ]));

        $response->assertOk();
        $response->assertJsonStructure([
            'errors' => [
                '*' => [
                    'validation' => [
                        'workspace_id',
                    ],
                ],
            ],
        ]);
    }

    public function testCanCreateChannel()
    {
        $user = factory(User:: class)->create();
        $workspace = factory(Workspace::class)->create();
        $user->workspaces()->save($workspace, ['role' => 'member']);

        $response = $this->actingAs($user, 'api')
            ->postJson('/graphql', $this->validParams([
                'variables' => [
                    'workspace_id' => $workspace->getKey(),
                ],
            ]));

        $response->assertOk();
        $response->assertJsonStructure([
            'data' => [
                'createChannel' => [
                    'id',
                    'name',
                ],
            ],
        ]);

        $workspace->refresh();
        $this->assertCount(1, $workspace->channels);
    }
}
