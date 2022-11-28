<?php declare(strict_types=1);

namespace GraphQL\Collections\Type;

use Exception;

use GraphQL\Collections\Data\Collection;
use GraphQL\Collections\Data\Distribution;
use GraphQL\Collections\Data\DistributionElement;
use GraphQL\Collections\Data\DataSource;
use GraphQL\Collections\Types;
use GraphQL\Type\Definition\NonNull;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\ListOfType;
use function method_exists;
use function ucfirst;

class DistributionType extends ObjectType
{

    public function __construct()
    {
        parent::__construct([
            'name' => 'Distribution',
            'description' => 'Distribution over the top X elements of a specific asset',
            'fields' => static fn (): array => [
                'id' => [
                    'type' => Types::string(),
                ],
                'name' => [
                    'type' => Types::string(),
                ],
                'volume' => [
                    'type' => Types::float(),
                ],
                'elements' => [
                    'type' => new ListOfType(Types::distributionElement()),
                    'args' => [
                        'limit' => [
                            'type' => Types::int(),
                            'defaultValue' => 30,
                        ],
                    ],
                ]
            ],
            'interfaces' => [Types::node()],
            'resolveField' => function (Distribution $distribution, $args, $context, ResolveInfo $info) {
                $fieldName = $info->fieldName;

                $method = 'resolve' . ucfirst($fieldName);
                if (method_exists($this, $method)) {
                    return $this->{$method}($distribution, $args, $context, $info);
                }

                return $distribution->{$fieldName};
            },
        ]);
    }

    /**
     * @param array{limit: int, after?: string} $args
     *
     * @return array<int, Comment>
     */
    public function resolveElements(Distribution $distribution, array $args): array
    {
        return DataSource::findElements(
            $distribution->id,
            $args['limit']
        );
    }
}