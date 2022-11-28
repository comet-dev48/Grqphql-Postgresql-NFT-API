<?php declare(strict_types=1);

namespace GraphQL\Collections\Data;

use GraphQL\Utils\Utils;

class Collection
{
    public int $id;

    public string $name;

    public string $img;

    public string $short_description;

    public string $description;

    public string $contract;

    public float $volume;

    public int $transfers;

    public int $minters;

    public float $market_cap;

    public float $floor_price;

    public float $avg_price;

    public int $owners;

    public int $circulating_supply;

    public float $volume_change;

    public float $market_cap_eth;

    public float $floor_price_change;

    public float $avg_price_change;

    public array $volume_chart;

    public string $first_mint;

    public string $fomo;

    public int $mints;
    
    public float $mints_change;

    public float $mint_volume;

    public float $mint_volume_change;

    public float $minters_change;

    public int $mint_whales;

    public float $mint_whales_change;

    public float $mint_cost;



    /**
     * @param array<string, mixed> $data
     */
    public function __construct(array $data)
    {
        Utils::assign($this, $data);
    }
}