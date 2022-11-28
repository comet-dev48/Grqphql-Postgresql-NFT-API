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

class FormatType extends ObjectType
{
    public function __construct()
    {
        parent::__construct([
            'name' => 'Format',
            'description' => 'Formatting rules',
            'fields' => static fn (): array => [
                'date' => [
                    'type' => Types::string(),
                ],
                'datetime' => [
                    'type' => Types::string(),
                ],
                'price' => [
                    'type' => Types::string(),
                ],
                'eth_price' => [
                    'type' => Types::string(),
                ],
                'percentage' => [
                    'type' => Types::string(),
                ],
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