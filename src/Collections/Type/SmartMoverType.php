<?php declare(strict_types=1);

namespace GraphQL\Collections\Type;

use Exception;

use GraphQL\Collections\Data\SmartMover;
use GraphQL\Collections\Types;
use GraphQL\Type\Definition\NonNull;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;
use function method_exists;
use function ucfirst;

class SmartMoverType extends ObjectType
{
    public function __construct()
    {
        parent::__construct([
            'name' => 'SmartMover',
            'description' => 'Smart Movers transactions',
            'fields' => static fn (): array => [
                'mover_name' => [
                    'type' => Types::string(),
                ],
                'mover_address' => [
                    'type' => Types::string(),
                ],
                'mover_type' => [
                    'type' => Types::string(),
                ],
                'move' => [
                    'type' => Types::string(),
                ],
                'quantity' => [
                    'type' => Types::int(),
                ],
                'collection' => [
                    'type' => Types::string(),
                ],
                'id' => [
                    'type' => Types::int(),
                ],
                'value' => [
                    'type' => Types::float(),
                ],
                'gas_fee' => [
                    'type' => Types::float(),
                ],
                'when' => [
                    'type' => Types::string()
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