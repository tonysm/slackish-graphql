<?php

namespace Tests\Feature\GraphQL;

use App\User;
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

        return [
            'query' => $mutation,
            'variables' => [
                'name' => null,
                'workspace_id' => null,
            ],
        ];
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

    public function testCanCreateChannel()
    {
    }
}
