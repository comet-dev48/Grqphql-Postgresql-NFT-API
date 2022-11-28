<?php declare(strict_types=1);

namespace GraphQL\Collections\Type;

use Exception;
use GraphQL\Collections\AppContext;
use GraphQL\Collections\Data\Collection;
use GraphQL\Collections\Data\Nft;
use GraphQL\Collections\Data\Event;
use GraphQL\Collections\Data\Format;
use GraphQL\Collections\Data\Distribution;
use GraphQL\Collections\Data\DistributionElement;
use GraphQL\Collections\Data\SmartMover;
use GraphQL\Collections\Data\Sale;
use GraphQL\Collections\Data\DataSource;
use GraphQL\Collections\Types;
use GraphQL\Type\Definition\ListOfType;
use GraphQL\Type\Definition\NonNull;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;

class QueryType extends ObjectType
{
    public function __construct()
    {
        parent::__construct([
            'name' => 'Query',
            'description' => 'Standard operation for Rarespot GraphQL API',
            'fields' => [
                'collection' => [
                    'type' => Types::collection(),
                    'description' => 'Returns collection by internal RS id ',
                    'args' => [
                        'id' => new NonNull(Types::id()),
                    ],
                ],
                'collections' => [
                    'type' => new ListOfType(Types::collection()),
                    'description' => 'Returns list of collections',
                    'args' => [
                        'limit' => [
                            'type' => Types::int(),
                            'description' => 'Number of collections to be returned',
                            'defaultValue' => 100,
                        ],
                        'period' => [
                            'type' => Types::string(),
                            'description' => 'Unit of time to be analyzed, example 7d for 7 days',
                            'defaultValue' => '7d',
                        ],
                    ],
                ],
                'nft' => [
                    'type' => Types::nft(),
                    'description' => 'Returns NFT by internal RS id ',
                    'args' => [
                        'token_adress' => [
                            'type' => Types::string(),
                            'description' => 'key for token adress',
                            'defaultValue' => '',
                        ],
                        'token_id' => [
                            'type' => Types::int(),
                            'description' => 'id of token',
                            'defaultValue' => 0,
                        ],
                    ],
                ],
                'nfts' => [
                    'type' => new ListOfType(Types::nft()),
                    'description' => 'Returns list of collections',
                    'args' => [
                        'token_adress' => [
                            'type' => Types::string(),
                            'description' => 'key for token adress',
                            'defaultValue' => '',
                        ],
                        'limit' => [
                            'type' => Types::int(),
                            'description' => 'Number of collections to be returned',
                            'defaultValue' => 100,
                        ],
                        'rank_by' => [
                            'type' => Types::string(),
                            'description' => 'Key for rank type',
                            'defaultValue' => '',
                        ],
                    ],
                ],
                'event' => [
                    'type' => Types::event(),
                    'description' => 'Returns event by id',
                    'args' => [
                        'id' => new NonNull(Types::id()),
                    ],
                ],
                'events' => [
                    'type' => new ListOfType(Types::event()),
                    'description' => 'Returns list of events',
                    'args' => [
                        'limit' => [
                            'type' => Types::int(),
                            'description' => 'Number of events to be returned',
                            'defaultValue' => 10,
                        ],
                    ],
                ],
                'format' => [
                    'type' => Types::format(),
                    'description' => 'Returns formatting rules',
                    'args' => [
                        'lang' => new NonNull(Types::string()),
                    ],
                ],
                'distribution' => [
                    'type' => Types::distribution(),
                    'description' => 'Returns distribution of elements',
                    'args' => [
                        'id' => Types::string(),
                    ],
                ], 
                'smartMovers' => [
                    'type' => new ListOfType(Types::smartMover()),
                    'description' => 'Smart Movers',
                    'args' => [
                        'limit' => [
                            'type' => Types::int(),
                            'description' => 'Number of rows to be returned',
                            'defaultValue' => 30,
                        ],
                    ],
                ],
                'stories' => [
                    'type' => new ListOfType(Types::story()),
                    'description' => 'Returns subset of stories posted for this blog',
                    'args' => [
                        'after' => [
                            'type' => Types::id(),
                            'description' => 'Fetch stories listed after the story with this ID',
                        ],
                        'limit' => [
                            'type' => Types::int(),
                            'description' => 'Number of stories to be returned',
                            'defaultValue' => 10,
                        ],
                    ],
                ],
                'story' => [
                    'type' => Types::story(),
                    'args' => [
                        'id' => [
                            'type' => Types::id()
                        ],
                    ]
                ]
             ],
            'resolveField' => fn ($rootValue, array $args, $context, ResolveInfo $info) => $this->{$info->fieldName}($rootValue, $args, $context, $info),
        ]);
    }
    
    
    /**
     * @param null $rootValue
     * @param array{id: string} $args
     */
    public function collection($rootValue, array $args): ?Collection
    {
        return DataSource::findCollection((int) $args['id']);
    }

    /**
     * @param null                              $rootValue
     * @param array{limit: int} $args
     *
     * @return array<int, Collection>
     */
    public function collections($rootValue, array $args): array
    {
        return DataSource::findCollections(
            $args['limit']
        );
    }

    /**
     * @param null $rootValue
     * @param array{id: string} $args
     */
    public function nft($rootValue, array $args): ?Nft
    {
        return DataSource::findNft((int) $args['id']);
    }

    /**
     * @param null                              $rootValue
     * @param array{limit: int} $args
     *
     * @return array<int, Collection>
     */
    public function nfts($rootValue, array $args): array
    {
        return DataSource::findNfts(
            $args['limit']
        );
    }

     /**
     * @param null $rootValue
     * @param array{id: string} $args
     */
    public function event($rootValue, array $args): ?Event
    {
        return DataSource::findEvent((int) $args['id']);
    }

    /**
     * @param null                              $rootValue
     * @param array{limit: int} $args
     *
     * @return array<int, Collection>
     */
    public function events($rootValue, array $args): array
    {
        return DataSource::findEvents(
            $args['limit']
        );
    }

    /**
     * @param null $rootValue
     * @param array{lang: string} $args
     */
    public function format($rootValue, array $args): ?Format
    {
        return DataSource::findFormat( $args['lang']);
    }

    /**
     * @param null $rootValue
     * @param array{id: string} $args
     */
    public function distribution($rootValue, array $args): ?Distribution
    {
        return DataSource::findDistribution( $args['id']);
    }

    /**
     * @param null $rootValue
     * @param array{id: string} $args
     */
    public function smartMovers($rootValue, array $args): array
    {
        return DataSource::findSmartMovers( $args['limit']);
    }

    public function stories($rootValue, array $args): array
    {
        return DataSource::findStories(
            $args['limit'],
            isset($args['after'])
                ? (int) $args['after']
                : null
        );
    }

    public function story($rootValue, array $args){
        return DataSource::findStory(
            $args['id']
        );
    }

}