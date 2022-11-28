<?php declare(strict_types=1);
ini_set('display_errors', "1");
ini_set('display_startup_errors', "1");
error_reporting(E_ALL);

// Run local test server
// php -S localhost:8080 graphql.php

// Try query
// curl -d '{"query": "query { hello }" }' -H "Content-Type: application/json" http://localhost:8080

require_once __DIR__ . '/../vendor/autoload.php';
//install php e composer altair (x chrome)
//composer update
//TODO: Move to autoload
require_once 'Collections/Data/Collection.php';
require_once 'Collections/Data/NftMetaAttribute.php';
require_once 'Collections/Data/NftMetaData.php';
require_once 'Collections/Data/Nft.php';
require_once 'Collections/Data/Event.php';
require_once 'Collections/Data/Format.php';
require_once 'Collections/Data/Distribution.php';
require_once 'Collections/Data/DistributionElement.php';
require_once 'Collections/Data/SmartMover.php';
require_once 'Collections/Data/DataSource.php';
require_once 'Collections/Data/Story.php';
require_once 'Collections/Data/Comment.php';
require_once 'Collections/Type/CollectionType.php';
require_once 'Collections/Type/NftMetaAttributeType.php';
require_once 'Collections/Type/NftMetaDataType.php';
require_once 'Collections/Type/NftType.php';
require_once 'Collections/Type/NodeType.php';
require_once 'Collections/Type/EventType.php';
require_once 'Collections/Type/FormatType.php';
require_once 'Collections/Type/DistributionType.php';
require_once 'Collections/Type/DistributionElementType.php';
require_once 'Collections/Type/QueryType.php';
require_once 'Collections/Type/SmartMoverType.php';
require_once 'Collections/Type/StoryType.php';
require_once 'Collections/Type/CommentType.php';
require_once 'Collections/AppContext.php';
require_once 'Collections/Types.php';
require_once 'Utils/slogger.php';

require_once 'Collections/Data/db/queries.php';

use GraphQL\GraphQL;
use GraphQL\Collections\AppContext;
use GraphQL\Collections\Data\DataSource;
use GraphQL\Collections\Data\Event;
use GraphQL\Collections\Data\Format;
use GraphQL\Collections\Data\Distribution;
use GraphQL\Collections\Data\DistributionElement;
use GraphQL\Collections\Data\Story;
use GraphQL\Collections\Data\Comment;
use GraphQL\Collections\Data\Nft;
use GraphQL\Collections\Type\QueryType;
use GraphQL\Collections\Type\EventType;
use GraphQL\Collections\Type\FormatType;
use GraphQL\Collections\Type\DistributionType;
use GraphQL\Collections\Type\DistributionElementType;
use GraphQL\Collections\Type\SmartMoverType;
use GraphQL\Collections\Type\NodeType;
use GraphQL\Collections\Type\StoryType;
use GraphQL\Collections\Type\CommentType;
use GraphQL\Collections\Type\NftType;
use GraphQL\Collections\Types;
use GraphQL\Server\StandardServer;
use GraphQL\Server\Helper;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Schema;

use GraphQL\Error\DebugFlag;

$debug = DebugFlag::INCLUDE_DEBUG_MESSAGE | DebugFlag::INCLUDE_TRACE;

/** @var Helper */
$helper;
$slogger = new Slogger;
$queries = new Queries;
$class = "graphql";

$slogger->debug($class, "GraphQl request received from " . $_SERVER['REMOTE_ADDR'] . " with method " . $_SERVER['REQUEST_METHOD']);

try {
    // Initialize our fake data source
    DataSource::init();

    // See docs on schema options:
    // https://webonyx.github.io/graphql-php/schema-definition/#configuration-options


    try {
        $schema = new Schema([
            'query' => new QueryType(),
            'typeLoader' => static fn(string $name): Type => Types::byTypeName($name),
        ]);
        //$schema->assertValid();
    } catch (GraphQL\Error\InvariantViolation $e) {
        echo $e->getMessage();
        $slogger->debug($class, $e->getTraceAsString());
    }

    // Prepare context that will be available in all field resolvers (as 3rd argument):
    $appContext = new AppContext();
    $currentlyLoggedInUser = DataSource::findCollection(0);
    assert($currentlyLoggedInUser !== null);
    $appContext->viewer = $currentlyLoggedInUser;
    $appContext->rootUrl = 'http://localhost:8080';
    $appContext->request = $_REQUEST;

    // See docs on server options:
    // https://webonyx.github.io/graphql-php/executing-queries/#server-configuration-options
    $server = new StandardServer([
        'schema' => $schema,
        'context' => $appContext,
    ]);

    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
        $slogger->warn($class, "Skip request processing becouse of HTTP Method OPTIONS");
        $response = [];
        $helper = new Helper();
        $helper->emitResponse($response, 200, false);
    } else {
        $response = $server->handleRequest();

        if ($response != null) {
            $slogger->debug($class, $response->toArray($debug));
        } else {
            $slogger->warn($class, "ERROR: response is null");
        }
    }
} catch (Throwable $error) {
    $slogger->warn($class, $error->getMessage());
    $slogger->warn($class, $error->getTraceAsString());
    StandardServer::send500Error($error, $debug);
}