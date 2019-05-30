<?php

namespace App\GraphQL\Types;

use App\Channel;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class ChannelType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Channel',
        'description' => 'A channel',
        'model' => Channel::class,
    ];

    public function fields()
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'The id of the channel',
            ],
            'name' => [
                'type' => Type::string(),
                'description' => 'The name of the channel',
            ],
        ];
    }
}
