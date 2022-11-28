<?php declare(strict_types=1);

namespace GraphQL\Collections\Type;

use Exception;

use GraphQL\Collections\Data\Collection;
use GraphQL\Collections\Data\Nft;
use GraphQL\Collections\Types;
use GraphQL\Type\Definition\NonNull;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;
use function method_exists;
use function ucfirst;

class NftType extends ObjectType
{
    public function __construct()
    {
        parent::__construct([
            'name' => 'Nft',
            'description' => 'Single NFT',
            'fields' => static fn(): array => [
                'token_address' => [
                    'type' => Types::string(),
                ],
                'token_id' => [
                    'type' => Types::string(),
                ],
                'owner_of' => [
                    'type' => Types::string(),
                ],
                'token_hash' => [
                    'type' => Types::string(),
                ],
                'block_number' => [
                    'type' => Types::string(),
                ],
                'block_number_minted' => [
                    'type' => Types::string(),
                ],
                'contract_type' => [
                    'type' => Types::string(),
                ],
                'token_uri' => [
                    'type' => Types::string(),
                ],
                'metadata' => [
                    'type' => Types::string(),
                ],
                'normalized_metadata' => [
                    'type' => Types::nftMetaData(),
                ],
                'minter_address' => [
                    'type' => Types::string(),
                ],
                'last_token_uri_sync' => [
                    'type' => Types::string(),
                ],
                'last_metadata_sync' => [
                    'type' => Types::string(),
                ],
                'amount' => [
                    'type' => Types::string(),
                ],
                'name' => [
                    'type' => Types::string(),
                ],
                'symbol' => [
                    'type' => Types::string(),
                ],
            ],
            'interfaces' => [Types::node()],
            'resolveField' => function (Nft $nft, $args, $context, ResolveInfo $info) {
                $fieldName = $info->fieldName;

                $method = 'resolve' . ucfirst($fieldName);
                if (method_exists($this, $method)) {
                    return $this->{$method}($nft, $args, $context, $info);
                }

                return $nft->{$fieldName};
            },
        ]);
    }

}