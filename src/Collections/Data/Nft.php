<?php declare(strict_types=1);

namespace GraphQL\Collections\Data;

use GraphQL\Utils\Utils;

class Nft
{
    public string $token_address;

    public string $token_id;

    public string $owner_of;

    public string $token_hash;

    public string $block_number;

    public string $block_number_minted;
    
    public string $contract_type;

    public string $token_uri;
    
    public string $metadata;

    public NftMetaData $normalized_metadata;

    public string $minter_address;

    public string $last_token_uri_sync;

    public string $last_metadata_sync;

    public string $amount;

    public string $name;

    public string $symbol;

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