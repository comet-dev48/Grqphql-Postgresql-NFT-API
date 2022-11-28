<?php declare(strict_types=1);

namespace GraphQL\Collections\Type;

use Exception;
use GraphQL\Collections\Data\Collection;
use GraphQL\Collections\Types;
use GraphQL\Type\Definition\InterfaceType;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Utils\Utils;

class PriceType extends InterfaceType
{
    public function __construct()
    {
        parent::__construct([
            'name' => 'Price',
            'fields' => [
                'value' => Types::float(),
                'currency' => [
                    'type' => Types::string(),
                ],
            ],
            'resolveType' => [$this, 'resolvePriceType'],
        ]);
    }
}