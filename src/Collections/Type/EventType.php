<?php declare(strict_types=1);

namespace GraphQL\Collections\Type;

use Exception;

use GraphQL\Collections\Data\Collection;
use GraphQL\Collections\Types;
use GraphQL\Type\Definition\NonNull;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;
use function method_exists;
use function ucfirst;

class EventType extends ObjectType
{
    public function __construct()
    {
        parent::__construct([
            'name' => 'Event',
            'description' => 'NFT Calendar Event',
            'fields' => static fn (): array => [
                'id' => Types::id(),
                'url_event' => [
                    'type' => Types::string(),
                ],
                'event_name' => [
                    'type' => Types::string(),
                ],
                'image_link' => [
                    'type' => Types::string(),
                ],
                'verified' => [
                    'type' => Types::boolean(),
                ],
                'start_date' => [
                    'type' => Types::string(),
                ],
                'end_date' => [
                    'type' => Types::string(),
                ],
                'website_link' => [
                    'type' => Types::string(),
                ],
                'twitter_link' => [
                    'type' => Types::string(),
                ],
                'discord_link' => [
                    'type' => Types::string(),
                ],
                'marketplace' => [
                    'type' => Types::string(),
                ],
                'marketplace_link' => [
                    'type' => Types::string(),
                ],
                'blockchain' => [
                    'type' => Types::string(),
                ],
                'blockchain_link' => [
                    'type' => Types::string(),
                ],
                'description' => [
                    'type'=> Types::string(),
                ]
            ],
            'interfaces' => [Types::node()],
            'resolveField' => function ($collection, $args, $context, ResolveInfo $info) {
                $fieldName = $info->fieldName;

                $method = 'resolve' . ucfirst($fieldName);
                if (method_exists($this, $method)) {
                    return $this->{$method}($collection, $args, $context, $info);
                }

                return $collection->{$fieldName};
            },
        ]);
    }
}