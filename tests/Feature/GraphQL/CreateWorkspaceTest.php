<?php

namespace Tests\Feature\GraphQL;

use App\User;
use Tests\TestCase;
use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateWorkspaceTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

    private function validParams(array $overrides = [])
    {
        $mutation = <<<'EOL'
mutation workspaces($name: String) {
    createWorkspace(name: $name) {
        id
        name
        default_channel_id
        channels {
            id
            name
        }
    }
}
EOL;

        return array_replace_recursive([
            'query' => $mutation,
            'variables' => [
                'name' => $this->faker->company,
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
        $user = factory(User::class)->create();

        $response = $this->actingAs($user, 'api')
            ->postJson('/graphql', $this->validParams([
                'variables' => [
                    'name' => null,
                ],
            ]));

        $response->assertOk();
        $response->assertJsonStructure([
            'errors' => [
                0 => [
                    'validation' => [
                        'name',
                    ],
                ],
            ],
        ]);
    }

    public function testCanCreateWorkspace()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user, 'api')
            ->postJson('/graphql', $this->validParams());

        $response->assertOk();
        $response->assertJsonStructure([
            'data' => [
                'createWorkspace' => [
                    'id',
                    'name',
                    'default_channel_id',
                    'channels' => [
                        '*' => [
                            'id',
                            'name',
                        ],
                    ],
                ],
            ],
        ]);

        $this->assertCount(1, $user->workspaces);
        $this->assertCount(1, $user->workspaces->first()->channels);
    }
}
