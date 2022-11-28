<?php declare(strict_types=1);

namespace GraphQL\Collections\Data;

use GraphQL\Utils\Utils;

class DistributionElement
{

    public int $id;

    public string $name;

    public float $value;

    public int $cardinality;

    public array $cardinalityGrouped;

    public string $format;

    public float $value_percent;

    /**
     * @param array<string, mixed> $data
     */
    public function __construct(array $data)
    {
        Utils::assign($this, $data);
    }

}