<?php declare(strict_types=1);

namespace GraphQL\Collections\Data;

use GraphQL\Utils\Utils;

class NftMetaData
{

    public string $name;

    public string $description;

    public string $image;

    public string $external_link;

    public string $animation_url;

    public array $attributes;

    /*
        TODO:
            - blockchain
            - link to collection item
            - mint date
            - attributes list and values
            - ...
            ? value / price / floor price

    */



    /**
     * @param array<string, mixed> $data
     */
    public function __construct(array $data)
    {
        Utils::assign($this, $data);
    }
}