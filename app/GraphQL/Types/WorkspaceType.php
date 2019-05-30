<?php

namespace App\GraphQL\Types;

use App\Workspace;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class WorkspaceType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Workspace',
        'description' => 'A workspace',
        'model' => Workspace::class,
    ];

    public function fields()
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'The id of the workspace',
            ],
            'name' => [
                'type' => Type::string(),
                'description' => 'The name of the workspace',
            ],
            'default_channel_id' => [
                'type' => Type::int(),
                'description' => 'The id of the default channel',
            ],
            'channels' => ['type' => Type::listOf(\GraphQL::type('channel'))]
        ];
    }
}
