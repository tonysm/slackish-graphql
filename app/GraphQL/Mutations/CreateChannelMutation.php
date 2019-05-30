<?php

namespace App\GraphQL\Mutations;

use GraphQL\Type\Definition\Type;
use Illuminate\Validation\Rule;
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
                'rules' => [
                    'required',
                    'numeric',
                    Rule::exists('user_workspace', 'workspace_id')
                        ->where('user_workspace.user_id', auth()->id()),
                ],
            ],
        ];
    }

    public function resolve($root, $args)
    {
        /** @var \App\Workspace $workspace */
        $workspace = auth()->user()->workspaces()->find($args['workspace_id']);

        if (! $workspace) {
            return null;
        }

        return $workspace->createChannel($args['name']);
    }
}
