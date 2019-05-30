<?php

namespace Tests\Feature\GraphQL;

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
mutation channels($name: String, $workspaceId: Int) {
    createChannel(workspaceId: $workspaceId, name: $name) {
        id
        name
    }
}
EOL;

        return [
            'query' => $mutation,
            'variables' => [
                'name' => $this->faker->word,
                'workspaceId' => null,
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
    }

    public function testCanCreateChannel()
    {
    }
}
