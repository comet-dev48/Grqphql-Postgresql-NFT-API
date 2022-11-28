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

class CollectionType extends ObjectType
{
    public function __construct()
    {
        parent::__construct([
            'name' => 'Collection',
            'description' => 'Single NFT Collection details',
            'fields' => static fn (): array => [
                'id' => Types::id(),
                'name' => [
                    'type' => Types::string(),
                ],
                'img' => [
                    'type' => Types::string(),
                ],
                'description' => [
                    'type' => Types::string(),
                ],
                'short_description' => [
                    'type' => Types::string(),
                ],
                'contract' => [
                    'type' => Types::string(),
                ],
                'volume' => [
                    'type' => Types::float(),
                ],
                'transfers' => [
                    'type' => Types::int(),
                ],
                'minters' => [
                    'type' => Types::int(),
                ],
                'market_cap' => [
                    'type' => Types::float(),
                ],
                'floor_price' => [
                    'type' => Types::float(),
                ],
                'avg_price' => [
                    'type' => Types::float(),
                ],
                'owners' => [
                    'type' => Types::int(),
                ],
                'circulating_supply' => [
                    'type' => Types::int(),
                ],
                'volume_change' => [
                    'type' => Types::float(),
                ],
                'market_cap_eth' => [
                    'type' => Types::float(),
                ],
                'floor_price_change' => [
                    'type' => Types::float(),
                ],
                'avg_price_change' => [
                    'type' => Types::float(),
                ],
                'volume_chart' => [
                    'type' => Types::listOf(Types::int()),
                ],
                'first_mint' => [
                    'type' => Types::string(),
                ],
                'fomo' => [
                    'type' => Types::string(),
                ],
                'mints' => [
                    'type' => Types::int(),
                ],
                'mints_change' => [
                    'type' => Types::float(),
                ],
                'mint_volume' => [
                    'type' => Types::float(),
                ],
                'mint_volume_change' => [
                    'type' => Types::float(),
                ],
                'minters_change' => [
                    'type' => Types::float(),
                ],
                'mint_whales' => [
                    'type' => Types::int(),
                ],
                'mint_whales_change' => [
                    'type' => Types::float(),
                ],
                'mint_cost' => [
                    'type' => Types::float(),
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