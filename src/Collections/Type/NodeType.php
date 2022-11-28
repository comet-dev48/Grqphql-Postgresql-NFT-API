<?php declare(strict_types=1);

namespace GraphQL\Collections\Type;

use Exception;
use GraphQL\Collections\Data\Collection;
use GraphQL\Collections\Types;
use GraphQL\Type\Definition\InterfaceType;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Utils\Utils;

class NodeType extends InterfaceType
{
    public function __construct()
    {
        parent::__construct([
            'name' => 'Node',
            'fields' => [
                'id' => Types::id(),
            ],
            'resolveType' => [$this, 'resolveNodeType'],
        ]);
    }

    /**
     * @param mixed $object
     *
     * @return callable(): ObjectType
     */
    public function resolveNodeType($object)
    {
        if ($object instanceof Collection) {
            return Types::collection();
        }

        throw new Exception('Unknown type: ' . Utils::printSafe($object));
    }
}