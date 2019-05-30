<?php

namespace App\GraphQL\Mutations;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;
use Rebing\GraphQL\Support\Facades\GraphQL;

class CreateChannelMutation extends Mutation
{
    protected $attributes = [
        'name' => 'CreateChannelMutation'
    ];

    public function type()
    {
        return GraphQL::type('channel');
    }

    public function args()
    {
        return [
            'name' => [
                'name' => 'name',
                'type' => Type::string(),
                'rules' => ['required', 'string', 'max:255'],
            ],
            'workspace_id' => [
                'name' => 'workspace_id',
                'type' => Type::int(),
                'rules' => ['required', 'number'],
            ],
        ];
    }

    public function resolve($root, $args)
    {
    }
}
