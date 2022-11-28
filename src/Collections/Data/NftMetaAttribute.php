<?php declare(strict_types=1);

namespace GraphQL\Collections\Data;

use GraphQL\Utils\Utils;

class NftMetaAttribute
{


    public string $trait_type;

    public string $value;

    public string $display_type;

    public int    $max_value;

    public int    $trait_count;

    public int    $order;



    /**
     * @param array<string, mixed> $data
     */
    public function __construct(array $data)
    {
        Utils::assign($this, $data);
    }
}