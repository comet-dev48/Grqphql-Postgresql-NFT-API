<?php declare(strict_types=1);

namespace GraphQL\Collections\Data;

use GraphQL\Utils\Utils;

class SmartMover
{
    public string $mover_name;

    public string $mover_address;

    public string $mover_type;

    public string $move;

    public int $quantity;

    public string $collection;

    public int $id;

    public float $value;

    public float $gas_fee;

    public string $when;


    /**
     * @param array<string, mixed> $data
     */
    public function __construct(array $data)
    {
        Utils::assign($this, $data);
    }
}