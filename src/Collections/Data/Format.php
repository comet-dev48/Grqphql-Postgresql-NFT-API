<?php declare(strict_types=1);

namespace GraphQL\Collections\Data;

use GraphQL\Utils\Utils;

class Format
{
    public string $date;

    public string $datetime;

    public string $price;

    public string $eth_price;

    public string $percentage;

    /**
     * @param array<string, mixed> $data
     */
    public function __construct(array $data)
    {
        Utils::assign($this, $data);
    }

}