<?php

namespace App\GraphQL\Mutations;

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
        ];
    }

    public function resolve($root, $args)
    {
    }
}
