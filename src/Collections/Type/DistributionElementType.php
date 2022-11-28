<?php declare(strict_types=1);

namespace GraphQL\Collections\Type;

use Exception;

use GraphQL\Collections\Data\Collection;
use GraphQL\Collections\Data\DistributionElement;
use GraphQL\Collections\Types;
use GraphQL\Type\Definition\NonNull;
use GraphQL\Collections\Data\DataSource;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\ListOfType;
use function method_exists;
use function ucfirst;

class DistributionElementType extends ObjectType
{
    public function __construct()
    {
        parent::__construct([
            'name' => 'DistributionElement',
            'description' => 'Distribution element',
            'fields' => static fn (): array => [
                'id' => [
                    'type' => Types::int(),
                ],
                'name' => [
                    'type' => Types::string(),
                ],
                'cardinality' => [
                    'type' => Types::int(),
                ],
                'cardinalityGrouped' => [
                    'type' => new ListOfType(Types::distributionElement()),
                ],
                'value' => [
                    'type' => Types::float(),
                ],
                'format' => [
                    'type' => Types::string(),
                ],
                'value_percent' => [
                    'type' => Types::float(),
                ]
            ],
            'interfaces' => [Types::node()],
            'resolveField' => function (DistributionElement $distributionElement, $args, $context, ResolveInfo $info) {
                $fieldName = $info->fieldName;

                $method = 'resolve' . ucfirst($fieldName);
                if (method_exists($this, $method)) {
                    return $this->{$method}($distributionElement, $args, $context, $info);
                }

                return $distributionElement->{$fieldName};
            },
        ]);
    }

    /**
     * @param array{limit: int, after?: string} $args
     *
     * @return array<int, Comment>
     */
    public function resolveCardinalityGrouped(DistributionElement $element, array $args): array
    {
        return DataSource::findCardinalityGrouped(
            $element->id
        );
    }
}