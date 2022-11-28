<?php

declare(strict_types=1);

namespace GraphQL\Collections\Data;

use DateTime;

ini_set('display_errors', "1");
ini_set('display_startup_errors', "1");
error_reporting(E_ALL);

use function array_filter;
use function array_keys;
use function array_map;
use function array_search;
use function array_slice;
use function array_values;
use function count;
use function in_array;
use function rand;

$class = "DataSource";

//TODO: move to config file 
$collectionSource = "MOCK";
$eventSource = "MOCK";
$nftSource = "RND";

//Mock type can be RND or MOCK , first create random info, second one return the mocked data
$mockType = "RND";
$rnd_names = array("Neo", "Morpheus", "Trinity", "Cypher", "Tank", "Monkeys", "Apes", "Gamers", "Teddy bears", "Candies", "Knights", "Lakers", "Warriors", "Timberwolvers", "Bulls");
$rnd_colors = array("White", "Black", "Green", "Blue", "Red", "Gold", "Silver", "Orange", "Dark", "Divine", "Rare", "Unique", "Common", "Crazy", "Bored", "Happy", "Sad", "Sandy");
$rnd_img = array("https://ipfs.io/ipfs/QmQqzMTavQgT4f4T5v6PWBp7XNKtoPmC9jvn12WPT3gkSE");

$period_values = array(
    "1m" => 1,
    "5m" => 5,
    "10m" => 10,
    "30m" => 30,
    "1h" => 60,
    "6h" => 360,
    "12h" => 720,
    "24h" => 1440,
    "1d" => 1440,
    "7d" => 10080,
    "30d" => 43200
);

$fomo = array("Low", "Medium", "High");

if ($collectionSource != "MOCK" || $eventSource != "MOCK" || $nftSource != "MOCK") {
    require_once('db/connect.php');
}

/**
 * This is just a simple in-memory data holder for the sake of example.
 * Data layer for real app may use Doctrine or query the database directly (e.g. in CQRS style).
 */
class DataSource
{

    /** @var array<int, Collection> */
    private static array $collections = [];

    private static array $nfts = [];

    private static array $stories = [];
    private static array $comments = [];
    private static array $storyComments = [];

    /** @var array<int, Event> */
    private static array $events = [];

    private static array $formats = [];

    private static array $grouped = [];
    private static array $element2cardinalityGrouped = [];

    private static array $distributions = [];
    private static array $elements = [];
    private static array $distribution2element = [];

    public static function init(): void
    {
        //TODO: setting null to one of the field raises error. That has to be fixed. 'null' containing fields here will be normally boolean null values from the database upcoming events.
        self::$events = [
            1 => new Event([
                'id' => 1,
                'url_event' => 'https://nftcalendar.io/event/embers/',
                'event_name' => 'Embers',
                'image_link' => 'https://nftcalendar.io/storage/uploads/events/2022/2/dpusazRUqM5jrjrQ0S3L2H19fbCQKxAMefj3INDr.gif',
                'start_date' => 'February 28, 2022',
                'end_date' => 'March 07, 2022',
                'website_link' => 'null',
                'twitter_link' => 'null',
                'discord_link' => 'null',
                'marketplace' => 'opensea',
                'marketplace_link' => 'https://opensea.io/',
                'blockchain' => 'ethereum',
                'blockchain_link' => 'https://opensea.io/',
                'description' => 'Embers are a collection of 5,555 burning hot NFTs living in the core of the blockchain. Each individual Ember is carefully curated from over 150 traits, along with some incredibly rare 1/1s that have traits that can\'t be found from any other Ember.Our vision is to create an amazing project that will shed light, joy, love, and creativity! Fire Sale ( WL ) members may mint an Ember for 0.1 ETH.Public Sale will be a Dutch Auction that will decrease from a maximum of 0.3 ETH to a minimum of 0.15 ETH.Our Fire Sale mint is on March 26, 2022 7:00 PM UTCOur Public Sale mint is on March 27, 2022 7:00 PM UTCOur official links are:https://twitter.com/embersnfthttps://instagram.com/embersnfthttps://embersnft.comThere you could find our roadmap! We\'ve already completed a couple things, such as: * Free NFT Giveaways* Donation of $50,000 to the Red Cross* We are also allocating 25% of mint funds for the longevity of the project, as well as 50% of royalties.We\'re implementing a DAO system so that every holder can have a say in the project. We are also adding staking, so that our members can earn tokens just by holding onto their Ember!Additionally, we are going to be awarding our holders with collaborations with other projects, as well as giving them airdrops and other special benefits!'
            ]),
            2 => new Event([
                'id' => 2,
                'url_event' => 'https://nftcalendar.io/event/embers/',
                'event_name' => 'Mock 2',
                'image_link' => 'https://nftcalendar.io/storage/uploads/events/2022/2/dpusazRUqM5jrjrQ0S3L2H19fbCQKxAMefj3INDr.gif',
                'start_date' => 'February 28, 2022',
                'end_date' => 'March 07, 2022',
                'website_link' => 'null',
                'twitter_link' => 'null',
                'discord_link' => 'null',
                'marketplace' => 'opensea',
                'marketplace_link' => 'https://opensea.io/',
                'blockchain' => 'ethereum',
                'blockchain_link' => 'https://opensea.io/',
                'description' => 'Mocks are a collection of 5 burning hot NFTs living in the core of the blockchain. Each individual Ember is carefully curated from over 150 traits, along with some incredibly rare 1/1s that have traits that can\'t be found from any other Ember.Our vision is to create an amazing project that will shed light, joy, love, and creativity! Fire Sale ( WL ) members may mint an Ember for 0.1 ETH.Public Sale will be a Dutch Auction that will decrease from a maximum of 0.3 ETH to a minimum of 0.15 ETH.Our Fire Sale mint is on March 26, 2022 7:00 PM UTCOur Public Sale mint is on March 27, 2022 7:00 PM UTCOur official links are:https://twitter.com/embersnfthttps://instagram.com/embersnfthttps://embersnft.comThere you could find our roadmap! We\'ve already completed a couple things, such as: * Free NFT Giveaways* Donation of $50,000 to the Red Cross* We are also allocating 25% of mint funds for the longevity of the project, as well as 50% of royalties.We\'re implementing a DAO system so that every holder can have a say in the project. We are also adding staking, so that our members can earn tokens just by holding onto their Ember!Additionally, we are going to be awarding our holders with collaborations with other projects, as well as giving them airdrops and other special benefits!'
            ])
        ];

        self::$formats = [
            "en" => new Format([
                'date' => 'dd/mm/yyyy',
                'datetime' => 'dd/mm/yyyy HH:MM',
                'price' => '0,000,000.00 $',
                'eth_price' => 'E 0,000,000.00',
                'percentage' => '00.00 %',
            ])
        ];

        self::$distributions = [
            "minting_volume" => new Distribution([
                'id' => 'minting_volume',
                'name' => 'Top 10 volume distribution, 24h',
                'volume' => 100
            ]),
            "smart_mover_buys" => new Distribution([
                'id' => 'smart_mover_buys',
                'name' => 'Smart movers buys, 24h',
                'volume' => 0
            ]),
            "smart_mover_sells" => new Distribution([
                'id' => 'smart_mover_sells',
                'name' => 'Smart movers sells, 24h',
                'volume' => 0
            ]),
            "smart_mover_mint" => new Distribution([
                'id' => 'smart_mover_mint',
                'name' => 'Smart movers mint, 24h',
                'volume' => 0
            ]),
            "collection_listing_and_floor" => new Distribution([
                'id' => 'collection_listing_and_floor',
                'name' => 'Listing & Floor, 24h',
                'volume' => 0
            ]),
            "collection_volume_and_price" => new Distribution([
                'id' => 'collection_volume_and_price',
                'name' => 'Volume & Price, 24h',
                'volume' => 0
            ]),
        ];

        self::$grouped = [
            0 => new DistributionElement(['id' => 0, 'name' => 'Traders', 'value' => 15]),
            1 => new DistributionElement(['id' => 1, 'name' => 'Minters', 'value' => 9])
        ];

        self::$elements = [
            0 => new DistributionElement(['id' => 0, 'name' => 'Axie Infinity', 'value' => 400, 'format' => 'ETH_PRICE', 'value_percent' => 40, 'cardinality' => 0]),
            1 => new DistributionElement(['id' => 1, 'name' => 'Genesisi Shapez', 'value' => 290, 'format' => 'ETH_PRICE', 'value_percent' => 29, 'cardinality' => 0]),
            2 => new DistributionElement(['id' => 2, 'name' => 'Morphs Official', 'value' => 89.3, 'format' => 'ETH_PRICE', 'value_percent' => 8.93, 'cardinality' => 0]),
            3 => new DistributionElement(['id' => 3, 'name' => 'Smfers', 'value' => 72.4, 'format' => 'ETH_PRICE', 'value_percent' => 7.24, 'cardinality' => 0]),
            4 => new DistributionElement(['id' => 4, 'name' => 'Houspets', 'value' => 61.4, 'format' => 'ETH_PRICE', 'value_percent' => 6.14, 'cardinality' => 0]),
            5 => new DistributionElement(['id' => 5, 'name' => 'Not Your Bio', 'value' => 59.2, 'format' => 'ETH_PRICE', 'value_percent' => 5.92, 'cardinality' => 0]),
            6 => new DistributionElement(['id' => 6, 'name' => 'Stoned Pixel Human', 'value' => 51.2, 'format' => 'ETH_PRICE', 'value_percent' => 5.12, 'cardinality' => 0]),
            7 => new DistributionElement(['id' => 7, 'name' => 'Ninja Mfers Offical', 'value' => 48.8, 'format' => 'ETH_PRICE', 'value_percent' => 4.88, 'cardinality' => 0]),
            8 => new DistributionElement(['id' => 8, 'name' => 'Noundles Game', 'value' => 32.1, 'format' => 'ETH_PRICE', 'value_percent' => 3.21, 'cardinality' => 0]),
            9 => new DistributionElement(['id' => 9, 'name' => 'Genesis mana', 'value' => 28.3, 'format' => 'ETH_PRICE', 'value_percent' => 2.83, 'cardinality' => 0]),
            10 => new DistributionElement(['id' => 10, 'name' => 'Axie Infinity', 'value' => 400, 'format' => 'ETH_PRICE', 'value_percent' => 0, 'cardinality' => 11]),
            11 => new DistributionElement(['id' => 11, 'name' => 'Genesisi Shapez', 'value' => 290, 'format' => 'ETH_PRICE', 'value_percent' => 0, 'cardinality' => 45]),
            12 => new DistributionElement(['id' => 12, 'name' => 'Morphs Official', 'value' => 89.3, 'format' => 'ETH_PRICE', 'value_percent' => 0, 'cardinality' => 10]),
            13 => new DistributionElement(['id' => 13, 'name' => 'Smfers', 'value' => 72.4, 'format' => 'ETH_PRICE', 'value_percent' => 0, 'cardinality' => 74]),
            14 => new DistributionElement(['id' => 14, 'name' => 'Houspets', 'value' => 61.4, 'format' => 'ETH_PRICE', 'value_percent' => 0, 'cardinality' => 2]),
            15 => new DistributionElement(['id' => 15, 'name' => 'Not Your Bio', 'value' => 59.2, 'format' => 'ETH_PRICE', 'value_percent' => 0, 'cardinality' => 54]),
            16 => new DistributionElement(['id' => 16, 'name' => 'Stoned Pixel Human', 'value' => 51.2, 'format' => 'ETH_PRICE', 'value_percent' => 0, 'cardinality' => 10]),
            17 => new DistributionElement(['id' => 17, 'name' => 'Ninja Mfers Offical', 'value' => 48.8, 'format' => 'ETH_PRICE', 'value_percent' => 0, 'cardinality' => 99]),
            18 => new DistributionElement(['id' => 18, 'name' => 'Noundles Game', 'value' => 32.1, 'format' => 'ETH_PRICE', 'value_percent' => 0, 'cardinality' => 11]),
            19 => new DistributionElement(['id' => 19, 'name' => 'Genesis mana', 'value' => 28.3, 'format' => 'ETH_PRICE', 'value_percent' => 0, 'cardinality' => 4]),
            20 => new DistributionElement(['id' => 20, 'name' => 'Crypto Char', 'value' => 128.3, 'format' => 'ETH_PRICE', 'value_percent' => 0, 'cardinality' => 12]),
            21 => new DistributionElement(['id' => 21, 'name' => '2022-10-18T20:00', 'value' => 0.045]),
            22 => new DistributionElement(['id' => 22, 'name' => '2022-10-18T24:00', 'value' => 0.050]),
            23 => new DistributionElement(['id' => 23, 'name' => '2022-10-19T04:00', 'value' => 0.055]),
            24 => new DistributionElement(['id' => 24, 'name' => '2022-10-19T08:00', 'value' => 0.045]),
            25 => new DistributionElement(['id' => 25, 'name' => '2022-10-19T12:00', 'value' => 0.06]),
            26 => new DistributionElement(['id' => 26, 'name' => '2022-10-19T16:00', 'value' => 0.075]),
            27 => new DistributionElement(['id' => 27, 'name' => '2022-10-19T20:00', 'value' => 0.07]),
        ];

        self::$distribution2element = [
            "minting_volume" => [0, 1, 2, 3, 4, 5, 6, 7, 8, 9],
            "smart_mover_buys" => [10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20],
            "smart_mover_sells" => [10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20],
            "smart_mover_mint" => [10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20],
            "collection_listing_and_floor" => [21, 22, 23, 24, 25, 26, 27],
            "collection_volume_and_price" => [21, 22, 23, 24, 25, 26, 27],
        ];

        self::$element2cardinalityGrouped = [
            10 => [0, 1],
            11 => [0, 1],
            12 => [0, 1],
            13 => [0, 1],
            14 => [0, 1],
            15 => [0, 1],
            16 => [0, 1],
            17 => [0, 1],
            18 => [0, 1],
            18 => [0, 1],
            20 => [0, 1],
        ];

        self::$collections = [
            //Empty collection to initialize the context
            0 => new Collection([
                'id' => 0,
                'name' => 'rarespotStuff',
                'img' => 'https://ipfs.io/ipfs/QmQqzMTavQgT4f4T5v6PWBp7XNKtoPmC9jvn12WPT3gkSE',
                'description' => 'Only for Rarespotters',
                'short_description' => '',
                'volume' => 0,
                'contract' => 'rare_contract',
                'transfers' => 0,
                'minters' => 0,
                'market_cap' => 0,
                'floor_price' => 0,
                'avg_price' => 0,
                'owners' => 0,
                'circulating_supply' => 0,
                'volume_change' => 0,
                'market_cap_eth' => 0,
                'floor_price_change' => 0,
                'avg_price_change' => 0
            ]),
            1 => new Collection([
                'id' => 1,
                'name' => 'CryptoStuff',
                'img' => 'https://ipfs.io/ipfs/QmQqzMTavQgT4f4T5v6PWBp7XNKtoPmC9jvn12WPT3gkSE',
                'description' => 'descr',
                'short_description' => 'descr',
                'volume' => 23.121,
                'contract' => 'test_contract',
                'transfers' => 23,
                'minters' => 23,
                'market_cap' => 23,
                'floor_price' => 23,
                'avg_price' => 23,
                'owners' => 23,
                'circulating_supply' => 23,
                'volume_change' => 23,
                'market_cap_eth' => 23.12,
                'floor_price_change' => 2.3,
                'avg_price_change' => -1.12
            ]),
            2 => new Collection([
                'id' => 2,
                'name' => '3D Avatars By Psychdre',
                'img' => 'https://ipfs.io/ipfs/QmQqzMTavQgT4f4T5v6PWBp7XNKtoPmC9jvn12WPT3gkSE',
                'description' => 'Witness an awesome collection that shows viewers what dynamic graphics and pure creativity looks like. They are all created with 3D graphic rendering and dynamic effects in each unique NFT digital art piece. The manifested collection is titled as 3D Avatars By Psychdre and is available on Rarespot.io.',
                'short_description' => '2D avatars & characters turned into 3D animations by motion graphics artist Psychdre.  

                Supply for every piece is ONE-of-ONE.
                
                DM @Psychdre.eth to request your avatar modeled! ',
                'volume' => 1,
                'contract' => 'test_contract',
                'transfers' => 23,
                'minters' => 23,
                'market_cap' => 23,
                'floor_price' => 23,
                'avg_price' => 23,
                'owners' => 23,
                'circulating_supply' => 23,
                'volume_change' => 23,
                'market_cap_eth' => 23.12,
                'floor_price_change' => 2.3,
                'avg_price_change' => -1.12
            ])
        ];

        self::$nfts = [
            0 => new Nft([
                'token_address' => "0xb47e3cd837dDF8e4c57F05d70Ab865de6e193BBB",
                'token_id' => '15',
                'owner_of' => '0x9c83ff0f1c8924da96cb2fcb7e093f78eb2e316b',
                'token_hash' => '502cee781b0fb40ea02508b21d319ced',
                'block_number' => '88256',
                'block_number_minted' => '88256',
                'contract_type' => 'ERC721',
                'token_uri' => 'string',
                'metadata' => 'string',
                'normalized_metadata' => new NftMetaData([
                    'name' => 'Moralis Mug',
                    'description' => 'Moralis Coffee nug 3D Asset that can be used in 3D worldspaces. This NFT is presented as a flat PNG, a Unity3D Prefab and a standard fbx.',
                    'image' => 'https://arw2wxg84h6b.moralishost.com:2053/server/files/tNJatzsHirx4V2VAep6sc923OYGxvkpBeJttR7Ks/de504bbadadcbe30c86278342fcf2560_moralismug.png',
                    'external_link' => 'https://giphy.com/gifs/loop-recursion-ting-aaODAv1iuQdgI',
                    'animation_url' => 'https://giphy.com/gifs/food-design-donuts-o9ngTPVYW4qo8',
                    'attributes' => [
                        0 => new NftMetaAttribute([
                            "trait_type" => "Eye Color",
                            "value" => "hazel",
                            "display_type" => "string",
                            "max_value" => 100,
                            "trait_count" => 7,
                            "order" => 1
                        ])
                    ]
                ]),
                'minter_address' => '0x9c83ff0f1c8924da96cb2fcb7e093f78eb2e316b',
                'last_token_uri_sync' => 'string',
                'last_metadata_sync' => 'string',
                'amount' => '1',
                'name' => 'CryptoKitties',
                "symbol" => "RARI"
            ]),
            1 => new Nft([
                'token_address' => "34345345",
                'token_id' => '1345',
                'owner_of' => 'erwere',
                'token_hash' => '502cee781b0fb40ea02508b21d319ced',
                'block_number' => '88256',
                'block_number_minted' => '88256',
                'contract_type' => 'ERC721',
                'token_uri' => 'string',
                'metadata' => 'string',
                'normalized_metadata' => new NftMetaData([
                    'name' => 'Moralis Mug',
                    'description' => 'Moralis Coffee nug 3D Asset that can be used in 3D worldspaces. This NFT is presented as a flat PNG, a Unity3D Prefab and a standard fbx.',
                    'image' => 'https://arw2wxg84h6b.moralishost.com:2053/server/files/tNJatzsHirx4V2VAep6sc923OYGxvkpBeJttR7Ks/de504bbadadcbe30c86278342fcf2560_moralismug.png',
                    'external_link' => 'https://giphy.com/gifs/loop-recursion-ting-aaODAv1iuQdgI',
                    'animation_url' => 'https://giphy.com/gifs/food-design-donuts-o9ngTPVYW4qo8',
                    'attributes' => [
                        0 => new NftMetaAttribute([
                            "trait_type" => "Eye Color",
                            "value" => "hazel",
                            "display_type" => "string",
                            "max_value" => 100,
                            "trait_count" => 7,
                            "order" => 1
                        ])
                    ]
                ]),
                'minter_address' => '0x9c83ff0f1c8924da96cb2fcb7e093f78eb2e316b',
                'last_token_uri_sync' => 'string',
                'last_metadata_sync' => 'string',
                'amount' => '1',
                'name' => 'CryptoKitties',
                "symbol" => "RARI"
            ]),
        ];

        self::$stories = [
            1 => new Story(['id' => 1]),
            2 => new Story(['id' => 2]),
            3 => new Story(['id' => 3]),
        ];

        self::$comments = [
            // thread #1:
            100 => new Comment(['id' => 100, 'authorId' => 3, 'storyId' => 1, 'body' => 'Likes']),
            110 => new Comment(['id' => 110, 'authorId' => 2, 'storyId' => 1, 'body' => 'Reply <b>#1</b>', 'parentId' => 100]),
            111 => new Comment(['id' => 111, 'authorId' => 1, 'storyId' => 1, 'body' => 'Reply #1-1', 'parentId' => 110]),
            112 => new Comment(['id' => 112, 'authorId' => 3, 'storyId' => 1, 'body' => 'Reply #1-2', 'parentId' => 110]),
            113 => new Comment(['id' => 113, 'authorId' => 2, 'storyId' => 1, 'body' => 'Reply #1-3', 'parentId' => 110]),
            114 => new Comment(['id' => 114, 'authorId' => 1, 'storyId' => 1, 'body' => 'Reply #1-4', 'parentId' => 110]),
            115 => new Comment(['id' => 115, 'authorId' => 3, 'storyId' => 1, 'body' => 'Reply #1-5', 'parentId' => 110]),
            116 => new Comment(['id' => 116, 'authorId' => 1, 'storyId' => 1, 'body' => 'Reply #1-6', 'parentId' => 110]),
            117 => new Comment(['id' => 117, 'authorId' => 2, 'storyId' => 1, 'body' => 'Reply #1-7', 'parentId' => 110]),
            120 => new Comment(['id' => 120, 'authorId' => 3, 'storyId' => 1, 'body' => 'Reply #2', 'parentId' => 100]),
            130 => new Comment(['id' => 130, 'authorId' => 3, 'storyId' => 1, 'body' => 'Reply #3', 'parentId' => 100]),
            200 => new Comment(['id' => 200, 'authorId' => 2, 'storyId' => 1, 'body' => 'Me2']),
            300 => new Comment(['id' => 300, 'authorId' => 3, 'storyId' => 1, 'body' => 'U2']),
            // thread #2:
            400 => new Comment(['id' => 400, 'authorId' => 2, 'storyId' => 2, 'body' => 'Me too']),
            500 => new Comment(['id' => 500, 'authorId' => 2, 'storyId' => 2, 'body' => 'Nice!']),
        ];

        self::$storyComments = [
            1 => [100, 200, 300],
            2 => [400, 500],
        ];

        for ($i = 3; $i < 31; $i++) {
            array_push(
                self::$collections,
                new Collection([
                    'id' => $i,
                    'name' => $i . '-D Avatars By Psychdre',
                    'img' => 'https://ipfs.io/ipfs/QmQqzMTavQgT4f4T5v6PWBp7XNKtoPmC9jvn12WPT3gkSE',
                    'description' => 'Available on Rarespot.io.',
                    'short_description' => 'modeled! ',
                    'volume' => 1,
                    'contract' => 'test_contract',
                    'transfers' => 23,
                    'minters' => 23,
                    'market_cap' => 23,
                    'floor_price' => 23,
                    'avg_price' => 23,
                    'owners' => 23,
                    'circulating_supply' => 23,
                    'volume_change' => 23,
                    'market_cap_eth' => 23.12,
                    'floor_price_change' => 2.3,
                    'avg_price_change' => -1.12
                ])
            );
        }
    }

    public static function findStory(int $id): ? Story
    {
        return self::$stories[$id] ?? null;
    }

    public static function findComment(int $id): ? Comment
    {
        return self::$comments[$id] ?? null;
    }

    /**
     * @return array<int, Story>
     */
    public static function findStories(int $limit, ?int $afterId = null): array
    {
        $start = $afterId !== null
            ? (int) array_search($afterId, array_keys(self::$stories), true) + 1
            : 0;

        return array_slice(array_values(self::$stories), $start, $limit);
    }

    /**
     * @return array<int, Comment>
     */
    public static function findComments(int $storyId, int $limit = 5, ?int $afterId = null): array
    {
        $storyComments = self::$storyComments[$storyId] ?? [];

        $start = isset($afterId)
            ? (int) array_search($afterId, $storyComments, true) + 1
            : 0;
        $storyComments = array_slice($storyComments, $start, $limit);

        return array_map(
            static fn(int $commentId): Comment => self::$comments[$commentId],
            $storyComments
        );
    }

    public static function countComments(int $storyId): int
    {
        return isset(self::$storyComments[$storyId])
            ? count(self::$storyComments[$storyId])
            : 0;
    }

    public static function findEvent(int $id): ? Event
    {
        global $eventSource, $pdo, $slogger, $class;

        if ($eventSource != "MOCK") {
            if ($pdo != null) {
                $query = "
                    SELECT id, url_event, event_name, image_link, verified, start_date, end_date, website_link, 
                    twitter_link, discord_link, marketplace, marketplace_link, blockchain, blockchain_link, 
                    description, created_at, updated_at, deleted_at
                    FROM upcoming_events 
                    WHERE id = " . $id . " ";
                $slogger->debug($class, "Quering: " . $query);
                $stmt = $pdo->query($query);
                $res = $stmt->fetch();

                if ($res != null) {
                    $slogger->debug($class, "Fetching event " . $id . " data: " . $slogger->arrayToString($res));
                    return self::populateEvent($res);
                }
            } else {
                $slogger->warn($class, "No PDO");
                return null;
            }
        }
        return self::$events[$id] ?? null;
    }

    public static function findFormat(string $lang): ? Format
    {
        global $eventSource, $pdo, $slogger, $class;
        return self::$formats[$lang] ?? null;
    }


    public static function findEvents(int $limit): array
    {
        global $eventSource, $pdo, $slogger, $class, $event;
        $i = 1;
        $resArray = array();
        if ($eventSource != "MOCK" || $eventSource != "RND") {
            if ($pdo != null) {
                $query = "
                    SELECT id, url_event, event_name, image_link, verified, start_date, end_date, website_link, 
                    twitter_link, discord_link, marketplace, marketplace_link, blockchain, blockchain_link, 
                    description, created_at, updated_at, deleted_at
                    FROM upcoming_events
                    WHERE TO_DATE(end_date, 'Month DD, YYYY') >= NOW()
                    ORDER BY TO_DATE(start_date, 'Month DD, YYYY') ;
                ";
                $slogger->debug($class, "Quering: " . $query);
                foreach ($pdo->query($query) as $row) {
                    $slogger->debug($class, "Fetching event " . $i . " data: " . $slogger->arrayToString($row));
                    array_push($resArray, self::populateEvent($row));
                    $i++;
                }
                $slogger->debug($class, "Total results: " . $i);
                return $resArray;
            } else {
                $slogger->warn($class, "No PDO");
                return null;
            }
        }

        return array_slice(array_values(self::$events), 0, $limit);
    }

    public static function findCollection(int $id): ? Collection
    {
        global $collectionSource, $pdo, $slogger, $class;

        if ($collectionSource != "MOCK" && $id > 0) {
            $query = "SELECT tokenname, tokenid FROM token_handler WHERE id = " . $id . " ";
            $slogger->debug($class, "Quering: " . $query);
            $stmt = $pdo->query($query);
            $res = $stmt->fetch();

            //TODO: gestire il not found, ora da errore
            if ($res != null) {
                $slogger->debug($class, "Returning token: " . $res["tokenname"]);
                return populateCollection($res);
            }

            return new Collection(array());
        }

        return self::$collections[$id] ?? null;
    }

    /**
     * @return array<int, Collection>
     */
    public static function findCollections(int $limit = 30, string $period = "7d"): array
    {
        global $collectionSource, $pdo, $slogger, $class, $mockType, $period_values, $queries;

        $collections = [];

        //match period with accepted values
        $period_minutes = 0;
        if (array_key_exists($period, $period_values)) {
            $period_minutes = $period_values[$period];
        } else {
            $period_minutes = $period_values["7d"];
        }

        if ($collectionSource == "PRD") {
            $currentDate = date("Y-m-d");
            $currentHour = date("h");
            $currentMinute = date("i");
            $currentTimestamp = time();

            $query = 'SELECT * FROM "most_recent_minute_aggregate" ';
            $slogger->debug($class, "FindCollections running : " . $query);
            try {
                $ris = $pdo->query($query);
                //$slogger->debug($class , "Result: ".json_encode($ris));
            } catch (\Throwable $th) {
                $slogger->error($class, "Error: " . $pdo->errorInfo());
            }

            $i = 0;
            $calc = array();
            foreach ($ris as $row) {
                $slogger->debug($class, "Returning token: " . $row["collection_name"]);
                $slogger->debug($class, "Token: " . $slogger->arrayToString($row));
                array_push(
                    $collections,
                    new Collection([
                        'name' => $row["collection_name"],
                        'volume' => intval($row["volume"]),
                        'id' => $i,
                        'contract' => $row["collection"],
                        'img' => "",
                        'transfers' => 0,
                        'minters' => 0,
                        'market_cap' => intval($row["market_cap"]),
                        'floor_price' => intval($row["floor_price"]),
                        'avg_price' => intval($row["avg_value_perminute"]),
                        'owners' => intval($row["count_owners"]),
                        'circulating_supply' => 0,
                        'volume_change' => 0,
                        'market_cap_eth' => intval($row["market_cap"]),
                        'floor_price_change' => 0,
                        'avg_price_change' => 0,
                        'short_description' => "",
                        'description' => "",
                        'volume_chart' => array(),
                        'first_mint' => "",
                        'fomo' => "",
                        'mints' => 0,
                        'mints_change' => 0,
                        'mint_volume' => 0,
                        'mint_volume_change' => 0,
                        'minters_change' => 0,
                        'mint_whales' => 0,
                        'mint_whales_change' => 0,
                        'mint_cost' => 0
                    ])
                );
                $i++;
            }
            return $collections;
        }

        if ($mockType == "RND") {
            global $rnd_colors, $rnd_img, $rnd_names, $fomo;
            for ($i = 1; $i <= $limit; $i++) {
                $name = $rnd_colors[array_rand($rnd_colors, 1)] . ' ' . $rnd_names[array_rand($rnd_names, 1)];
                $base_volume = mt_rand(0, 300);
                $date_rnd = rand(1262055681, 1262055681);
                array_push(
                    $collections,
                    new Collection([
                        'id' => $i,
                        'name' => $name,
                        'img' => $rnd_img[array_rand($rnd_img, 1)],
                        'description' => $name . ' is now available on Rarespot.io.',
                        'short_description' => 'Collection ' . $name . ' launched.',
                        'volume' => mt_rand(0, 300),
                        'contract' => '0x287850ee043155d8E4Aa6656478f1fB98f52D822',
                        'transfers' => mt_rand(0, 100),
                        'minters' => mt_rand(0, 100),
                        'market_cap' => mt_rand(0, 100000),
                        'floor_price' => mt_rand(0, 50) / 100,
                        'avg_price' => mt_rand(0, 50) / 50,
                        'owners' => mt_rand(0, 5000),
                        'circulating_supply' => mt_rand(0, 20000),
                        'volume_change' => mt_rand(-100, 100),
                        'market_cap_eth' => mt_rand(0, 10000),
                        'floor_price_change' => mt_rand(-50, 50) / 100,
                        'avg_price_change' => mt_rand(-50, 50) / 100,
                        'volume_chart' => array(
                            $base_volume += mt_rand(-30, +30),
                            $base_volume += mt_rand(-30, +30),
                            $base_volume += mt_rand(-30, +30),
                            $base_volume += mt_rand(-30, +30),
                            $base_volume += mt_rand(-30, +30),
                            $base_volume += mt_rand(-30, +30),
                            $base_volume += mt_rand(-30, +30)
                        ),
                        'first_mint' => date("Y-m-d", $date_rnd),
                        'fomo' => $fomo[mt_rand(0, 2)],
                        'mints' => mt_rand(0, 500),
                        'mints_change' => mt_rand(-50, 50) / 100,
                        'mint_volume' => mt_rand(00, 500),
                        'mint_volume_change' => mt_rand(-100, 200) / 100,
                        'minters_change' => mt_rand(-50, 50) / 100,
                        'mint_whales' => mt_rand(0, 100),
                        'mint_whales_change' => mt_rand(-50, 50) / 100,
                        'mint_cost' => mt_rand(0, 50) / 100,
                    ])
                );
            }

            return $collections;
        }

        return array_slice(array_values(self::$collections), 0, $limit);
    }

    public static function findNft(string $token_address, int $token_id): ? Nft
    {
        global $nftSource, $pdo, $slogger, $class;

        $resArray = array();

        if ($nftSource != "MOCK" && $token_id > 0) {
            $query = "SELECT * FROM nfts WHERE token_address = '" . $token_address . "' and token_id = '" . $token_id . "' ";
            $stmt = $pdo->query($query);
            $res = $stmt->fetch();

            //TODO: Not found in database, call the fetchNftsfromMoralis function
            if (!$res) {

                //TODO: input the wallet address in fetchNftsfromMoralis function
                $owner_nfts = self::fetchNftsfromMoralis();

                foreach ($owner_nfts as $value) {
                    if ($value['token_address'] == $token_address && $value['token_id'] == (string) $token_id) {
                        self::storeNftAtDatabase($value);
                        $resArray = $value;
                        break;
                    }
                }

                return $resArray;
            }

            //TODO: input the wallet address in fetchNftsfromMoralis function
            $owner_nfts = self::fetchNftsfromMoralis();

            foreach ($owner_nfts as $value) {
                if ($value['token_address'] == $token_address && $value['token_id'] == (string) $token_id) {
                    self::updateNftAtDatabase($value);
                    break;
                }
            }

            return self::populateNft($res);
        }

        foreach (self::$nfts as $value) {
            if ($value['token_address'] == $token_address && $value['token_id'] == (string) $token_id) {
                $resArray = $value;
                break;
            }
        }
        return $resArray ?? null;
    }

    public static function findNfts(string $token_address, int $limit = 30, string $rank = "top"): array
    {
        global $nftSource, $pdo, $slogger, $class;

        $resArray = array();

        if ($nftSource != "MOCK") {
            $query = "SELECT * FROM nfts WHERE token_address = '" . $token_address . "'";
            $stmt = $pdo->query($query);
            $res = $stmt->fetch();
            //TODO: Not found in database, call the fetchNftsfromMoralis function
            if (!$res) {

                //TODO: input the wallet address in fetchNftsfromMoralis function
                $owner_nfts = self::fetchNftsfromMoralis();

                foreach ($owner_nfts as $value) {
                    if ($value['token_address'] == $token_address) {
                        self::storeNftAtDatabase($value);
                        array_push($resArray, $value);
                    }
                }

                return $resArray;
            }
            $owner_nfts = self::fetchNftsfromMoralis();

            foreach ($owner_nfts as $value) {
                if ($value['token_address'] == $token_address) {
                    self::updateNftAtDatabase($value);
                    array_push($resArray, $value);
                }
            }
            return $resArray;
        }

        foreach (self::$nfts as $value) {
            if ($value['token_address'] == $token_address) {
                array_push($resArray,$value);
            }
        }
        return array_slice(array_values($resArray), 0, $limit);
    }

    public static function fetchNftsfromMoralis(string $address = '0xd8da6bf26964af9d7eed9e03e53415d37aa96045'): ?array
    {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://deep-index.moralis.io/api/v2/" . $address . "/nft?chain=eth&format=decimal&normalizeMetadata=true",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
                "X-API-Key: 462SSySeykh8TS1Qe5IWAfwlOLJODA3nvxkGMR4YV3xx26yDvijksLg645QOmNXj",
                "accept: application/json"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        $response = json_decode($response, true);

        curl_close($curl);

        return $err ? NULL : (isset($response['result']) ? $response['result'] : NULL);
    }

    public static function storeNftAtDatabase($row)
    {
        global $pdo;
        $nowDate = (new DateTime())->format('Y-m-d H:i:s');
        $query = "insert into nfts (token_address,token_id,owner_of,token_hash,block_number,
        block_number_minted,contract_type,token_uri,m_name,m_description,m_image,m_external_link,m_animation_url,
        m_attributes,minter_address,last_token_uri_sync,last_metadata_sync,amount,name,symbol,last_update) 
         values(
            '" . $row['token_address'] . "'
            ,'" . $row['token_id'] . "'
            ,'" . $row['owner_of'] . "'
            ,'" . $row['token_hash'] . "'
            ,'" . $row['block_number'] . "'
            ,'" . $row['block_number_minted'] . "'
            ,'" . $row['contract_type'] . "'
            ,'" . $row['token_uri'] . "'
            ,'" . $row['normalized_metadata']['name'] . "'
            ,'" . $row['normalized_metadata']['description'] . "'
            ,'" . $row['normalized_metadata']['image'] . "'
            ,'" . $row['normalized_metadata']['external_link'] . "'
            ,'" . $row['normalized_metadata']['animation_url'] . "'
            ,'" . json_encode($row['normalized_metadata']['attributes']) . "'
            ,'" . $row['minter_address'] . "'
            ,'" . $row['last_token_uri_sync'] . "'
            ,'" . $row['last_metadata_sync'] . "'
            ,'" . $row['amount'] . "'
            ,'" . $row['name'] . "'
            ,'" . $row['symbol'] . "'
            ,'" . $nowDate . "'
            )";
        $stmt = $pdo->query($query);
    }

    public static function updateNftAtDatabase($row)
    {
        global $pdo;
        $nowDate = (new DateTime())->format('Y-m-d H:i:s');
        $query = "update nfts set token_address = 
            '" . $row['token_address'] . "'
            ,token_id='" . $row['token_id'] . "'
            ,owner_of='" . $row['owner_of'] . "'
            ,token_hash='" . $row['token_hash'] . "'
            ,block_number='" . $row['block_number'] . "'
            ,block_number_minted='" . $row['block_number_minted'] . "'
            ,contract_type='" . $row['contract_type'] . "'
            ,token_uri='" . $row['token_uri'] . "'
            ,m_name='" . $row['normalized_metadata']['name'] . "'
            ,m_description='" . $row['normalized_metadata']['description'] . "'
            ,m_image='" . $row['normalized_metadata']['image'] . "'
            ,m_external_link='" . $row['normalized_metadata']['external_link'] . "'
            ,m_animation_url='" . $row['normalized_metadata']['animation_url'] . "'
            ,m_attributes='" . json_encode($row['normalized_metadata']['attributes']) . "'
            ,minter_address='" . $row['minter_address'] . "'
            ,last_token_uri_sync='" . $row['last_token_uri_sync'] . "'
            ,last_metadata_sync='" . $row['last_metadata_sync'] . "'
            ,amount='" . $row['amount'] . "'
            ,name='" . $row['name'] . "'
            ,symbol='" . $row['symbol'] . "'
            ,token_id='" . $nowDate . "' 
             where token_address = '" . $row['token_address'] . "' and token_id = '" . $row['token_id'] . "'
            )";
        $stmt = $pdo->query($query);
    }

    public static function populateNft($row)
    {
        global $slogger, $class;
        //$calc = self::getCollectionAnalysis($row["collection"]);
        $slogger->debug($class, "populating: " . $row["name"]);

        $result = new Nft([
            'token_address' => $row['token_address'],
            'token_id' => $row['token_id'],
            'owner_of' => $row['owner_of'],
            'token_hash' => $row['token_hash'],
            'block_number' => $row['block_number'],
            'block_number_minted' => $row['block_number_minted'],
            'contract_type' => $row['contract_type'],
            'token_uri' => $row['token_uri'],
            'metadata' => $row['metadata'],
            'normalized_metadata' => new NftMetaData([
                'name' => $row['m_name'],
                'description' => $row['m_description'],
                'image' => $row['m_image'],
                'external_link' => $row['m_external_link'],
                'animation_url' => $row['m_animation_url'],
                'attributes' => [
                    0 => new NftMetaAttribute([
                        "trait_type" => $row['m_attributes'][0]['trait_type'],
                        "value" => $row['m_attributes'][0]['value'],
                        "display_type" => $row['m_attributes'][0]['display_type'],
                        "max_value" => $row['m_attributes'][0]['max_value'],
                        "trait_count" => $row['m_attributes'][0]['trait_count'],
                        "order" => $row['m_attributes'][0]['order']
                    ])
                ]
            ]),
            'minter_address' => $row['minter_address'],
            'last_token_uri_sync' => $row['last_token_uri_sync'],
            'last_metadata_sync' => $row['last_metadata_sync'],
            'amount' => $row['amount'],
            'name' => $row['name'],
            "symbol" => $row['symbol']
        ]);
        $slogger->debug($class, "nft added: " . $slogger->arrayToString($result));

        return $result;
    }

    public static function getCollectionAnalysis(string $contractAddress): array
    {
        global $collectionSource, $pdo, $slogger, $class;
        $calc = self::initCalculatedValue();

        if ($collectionSource == "PRD") {
            $q = "
                SELECT 
                    SUM(total_transfers) as transfers, 
                    AVG(avg_value) as average, 
                    SUM(tot_value) as total_value,
                    SUM(minters) as minters
                FROM minute_aggregate m
                WHERE contractaddress = '$contractAddress'
                LIMIT 1
            ";
            $s = $pdo->query($q);
            $tr = $s->fetch();
            $slogger->debug($class, "test transfers: " . $tr["transfers"]);
            $calc["minters"] = 1;
            $calc["transfers"] = $tr["transfers"] > 0 ? $tr["transfers"] : 1;
            $calc["volume"] = 1;
            $calc["market_cap"] = 1;
            $calc["floor_price"] = 1;
            $calc["avg_price"] = 1;
            $calc["owners"] = 1;
            $calc["circulating_supply"] = 1;
            $calc["volume_change"] = 1;
            $calc["market_cap_eth"] = 1;
            $calc["floor_price_change"] = 1;
            $calc["avg_price_change"] = 1;
            $calc["volume_chart"] = array();
            //...
        }
        return $calc;
    }

    public static function populateCollection($row)
    {
        global $slogger, $class;
        //$calc = self::getCollectionAnalysis($row["collection"]);
        $slogger->debug($class, "populating: " . $row["collection_name"]);
        $result = new Collection([
            'name' => $row["collection_name"],
            'volume' => intval($row["volume"]),
            'id' => 1,
            'contract' => $row["collection"],
            'img' => "",
            'transfers' => 0,
            'minters' => 0,
            'market_cap' => intval($row["market_cap"]),
            'floor_price' => intval($row["floor_price"]),
            'avg_price' => intval($row["avg_value_perminute"]),
            'owners' => intval($row["count_owners"]),
            'circulating_supply' => 0,
            'volume_change' => 0,
            'market_cap_eth' => intval($row["market_cap"]),
            'floor_price_change' => 0,
            'avg_price_change' => 0,
            'short_description' => "",
            'description' => "",
            'volume_chart' => array(),
            'first_mint' => "",
            'fomo' => "",
            'mints' => 0,
            'mints_change' => 0,
            'mint_volume' => 0,
            'mint_volume_change' => 0,
            'minters_change' => 0,
            'mint_whales' => 0,
            'mint_whales_change' => 0,
            'mint_cost' => 0
        ]);
        $slogger->debug($class, "Collection added: " . $slogger->arrayToString($result));
        return $result;
    }

    public static function populateEvent($row)
    {
        return new Event([
            'id' => intval($row["id"]),
            'url_event' => isset($row["url_event"]) ? $row["url_event"] : "",
            'event_name' => isset($row["event_name"]) ? $row["event_name"] : "",
            'image_link' => isset($row["image_link"]) ? $row["image_link"] : "",
            'start_date' => isset($row["start_date"]) ? $row["start_date"] : "",
            'end_date' => isset($row["end_date"]) ? $row["end_date"] : "",
            'website_link' => isset($row["website_link"]) ? $row["website_link"] : "",
            'twitter_link' => isset($row["twitter_link"]) ? $row["twitter_link"] : "",
            'discord_link' => isset($row["discord_link"]) ? $row["discord_link"] : "",
            'marketplace' => isset($row["marketplace"]) ? $row["marketplace"] : "",
            'marketplace_link' => isset($row["marketplace_link"]) ? $row["marketplace_link"] : "",
            'blockchain' => isset($row["blockchain"]) ? $row["blockchain"] : "",
            'blockchain_link' => isset($row["blockchain_link"]) ? $row["blockchain_link"] : "",
            'description' => isset($row["description"]) ? $row["description"] : ""
        ]);
    }

    public static function initCalculatedValue()
    {
        $calc = array();
        $calc["minters"] = 0;
        $calc["transfers"] = 0;
        $calc["volume"] = 0;
        $calc["market_cap"] = 0;
        $calc["floor_price"] = 0;
        $calc["avg_price"] = 0;
        $calc["owners"] = 0;
        $calc["circulating_supply"] = 0;
        $calc["volume_change"] = 0;
        $calc["market_cap_eth"] = 0;
        $calc["floor_price_change"] = 0;
        $calc["avg_price_change"] = 0;
        return $calc;
    }


    public static function findDistribution(string $id = "minting_volume"): ? Distribution
    {
        global $collectionSource, $pdo, $slogger, $class;

        $slogger->debug($class, "Finding distribution for id: " . $id);

        if ($collectionSource == "MOCK") {
            $slogger->debug($class, "Returning mock distribution:" . json_encode(self::$distributions[$id]));
            return self::$distributions[$id];
        }

        if ($collectionSource == "RND") {
            global $rnd_colors, $rnd_img, $rnd_names, $fomo;
            $slogger->debug($class, "Returning RND distribution for id: " . $id);

            $distributions = [
                $id => new Distribution([
                    'id' => $id,
                    'name' => 'Top volume distribution, 24h',
                    'volume' => mt_rand(0, 999)
                ])
            ];
            return $distributions[$id];
        }


        if ($collectionSource == "PRD") {
            return null;
        }

        return self::$distributions[$id] ?? null;
    }

    /**
     * @return array<int, DistributionElement>
     */
    public static function findElements(string $distributionId, int $limit = 10): array
    {
        global $collectionSource, $pdo, $slogger, $class, $rnd_colors, $rnd_names;

        $slogger->debug($class, "Finding elements for " . $distributionId);
        $distribution2element = self::$distribution2element[$distributionId] ?? [];

        if ($collectionSource == "RND") {

            //DO NOTHING

            /*self::$elements = array();
            $distribution2element[$distributionId] = array();
            $t = mt_rand(100,999);
            $trem = 100;
            for($i=0;$i<$limit;$i++){
            $val = mt_rand(0,$t);
            $trem -= $val/$t;
            array_push(
            self::$elements,
            new DistributionElement([
            'id'=> $i,
            'name'=> $rnd_colors[array_rand($rnd_colors,1)].' '.$rnd_names[array_rand($rnd_names,1)],
            'value'=> $val,
            'format'=> "ETH_PRICE",
            'value_percent'=> $val/$t,
            ])
            );
            array_push($distribution2element[$distributionId], $i);
            }*/
        }

        return array_map(
            static fn(int $distributionId): DistributionElement => self::$elements[$distributionId],
            $distribution2element
        );
    }

    /**
     * @return array<int, DistributionElement>
     */
    public static function findCardinalityGrouped(int $elementId): array
    {
        global $collectionSource, $pdo, $slogger, $class, $rnd_colors, $rnd_names;

        $slogger->debug($class, "Finding cardinality grouped for " . $elementId);
        $element2cardinalityGrouped = self::$element2cardinalityGrouped[$elementId] ?? [];

        return array_map(
            static fn(int $elementId): DistributionElement => self::$grouped[$elementId],
            $element2cardinalityGrouped
        );
    }

    /**
     * @return array<int, SmartMover>
     */
    public static function findSmartMovers(int $limit = 30): array
    {
        global $collectionSource, $pdo, $slogger, $class, $rnd_colors, $rnd_names;

        $slogger->debug($class, "Finding smart movers");

        $smartMovers = array();
        if ($collectionSource == "RND" || $collectionSource == "MOCK") {
            $types = array("Trader", "Minter");
            $names = array("Smart Trader", "Smart Minter");
            $address = array("0xbdd9", "0xfe66");
            $ops = array("Buy", "Sell", "Mint");

            for ($i = 0; $i < $limit; $i++) {
                $j = mt_rand(0, 1);
                $n = $rnd_colors[array_rand($rnd_colors, 1)] . ' ' . $rnd_names[array_rand($rnd_names, 1)];
                array_push($smartMovers, new SmartMover([
                    "mover_name" => $names[$j],
                    "mover_address" => $address[$j],
                    "mover_type" => $types[$j],
                    "move" => $ops[mt_rand(0, 2)],
                    "quantity" => mt_rand(1, 20),
                    "collection" => $n,
                    "id" => mt_rand(1, 9999),
                    "value" => mt_rand(1, 100) / 100,
                    "gas_fee" => mt_rand(1, 1000) / 10000,
                    "when" => mt_rand(1, 60) . "m ago"
                ]));
                $slogger->debug($class, "Filling movers with " . $n);
            }
            $slogger->debug($class, "movers filled with " . count($smartMovers) . " movers");
        }

        return $smartMovers;
    }
}