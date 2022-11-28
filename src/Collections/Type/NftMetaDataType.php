<?php declare(strict_types=1);

namespace GraphQL\Collections\Type;

use Exception;

use GraphQL\Collections\Types;
use GraphQL\Type\Definition\ListOfType;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;
use function method_exists;
use function ucfirst;

class NftMetaDataType extends ObjectType
{
    public function __construct()
    {
        parent::__construct([
            'name' => 'NftMetaData',
            'description' => 'NftMetaData rules',
            'fields' => static fn (): array => [
                'name' => [
                    'type' => Types::string(),
                ],
                'description' => [
                    'type' => Types::string(),
                ],
                'image' => [
                    'type' => Types::string(),
                ],
                'external_link' => [
                    'type' => Types::string(),
                ],
                'animation_url' => [
                    'type' => Types::string(),
                ],
                'attributes' => [
                    'type' => new ListOfType(Types::nftMetaAttribute()),
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