<?php

namespace App\GraphQL\Mutations;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;
use Rebing\GraphQL\Support\Facades\GraphQL;

class CreateWorkspaceMutation extends Mutation
{
    protected $attributes = [
        'name' => 'CreateWorkspaceMutation'
    ];

    public function type()
    {
        return GraphQL::type('workspace');
    }

    public function args()
    {
        return [
            'name' => [
                'name' => 'name',
                'type' => Type::string(),
                'rules' => ['required', 'string', 'max:255'],
            ],
        ];
    }

    public function resolve($root, $args)
    {
        /** @var \App\User $user */
        $user = auth()->user();

        $workspace = $user->createWorkspace($args['name']);

        return $workspace->load('channels');
    }
}
