<?php declare(strict_types=1);

namespace GraphQL\Collections\Data;

use GraphQL\Utils\Utils;

/*
    Aim of this type is to cover all the top X chart where is displayed the total volume of specific asset and its distribution into the top X elements with their values 
    It is created initially to handle the MINTING VOLUME DISTRIBUTION chart into the Mint Tracker page.
*/

class Distribution
{
    public string $id;

    public string $name;

    public float $volume;

    /**
     * @param array<string, mixed> $data
     */
    public function __construct(array $data)
    {
        Utils::assign($this, $data);
    }

}