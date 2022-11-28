<?php declare(strict_types=1);

namespace GraphQL\Collections\Type;

use Exception;

use GraphQL\Collections\Types;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;
use function method_exists;
use function ucfirst;

class NftMetaAttributeType extends ObjectType
{
    public function __construct()
    {
        parent::__construct([
            'name' => 'NftMetaAttribute',
            'description' => 'NftMetaAttribute rules',
            'fields' => static fn (): array => [
                'trait_type' => [
                    'type' => Types::string(),
                ],
                'value' => [
                    'type' => Types::string(),
                ],
                'display_type' => [
                    'type' => Types::string(),
                ],
                'max_value' => [
                    'type' => Types::int(),
                ],
                'trait_count' => [
                    'type' => Types::int(),
                ],
                'order' => [
                    'type' => Types::int(),
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