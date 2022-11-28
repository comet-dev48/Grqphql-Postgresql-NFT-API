<?php declare(strict_types=1);

namespace GraphQL\Collections;

use Closure;
use function count;
use Exception;
use function explode;
use GraphQL\Collections\Type\CollectionType;
use GraphQL\Collections\Type\NftType;
use GraphQL\Collections\Type\EventType;
use GraphQL\Collections\Type\FormatType;
use GraphQL\Collections\Type\NodeType;
use GraphQL\Collections\Type\DistributionType;
use GraphQL\Collections\Type\DistributionElementType;
use GraphQL\Collections\Type\NftMetaAttributeType;
use GraphQL\Collections\Type\NftMetaDataType;
use GraphQL\Collections\Type\SmartMoverType;
use GraphQL\Collections\Type\StoryType;
use GraphQL\Collections\Type\CommentType;
use GraphQL\Collections\Type\QueryType;
use GraphQL\Type\Definition\ScalarType;
use GraphQL\Type\Definition\ListOfType;
use GraphQL\Type\Definition\Type;
use function lcfirst;
use function method_exists;
use function preg_replace;
use function strtolower;

/**
 * Acts as a registry and factory for your types.
 *
 * As simplistic as possible for the sake of clarity of this example.
 * Your own may be more dynamic (or even code-generated).
 */
final class Types
{
    /** @var array<string, Type> */
    private static array $types = [];

    public static function collection(): callable
    {
        return self::get(CollectionType::class);
    }

    public static function nft(): callable
    {
        return self::get(NftType::class);
    }

    public static function event(): callable
    {
        return self::get(EventType::class);
    }

    public static function format(): callable
    {
        return self::get(FormatType::class);
    }

    public static function distribution(): callable
    {
        return self::get(DistributionType::class);
    }

    public static function distributionElement(): callable
    {
        return self::get(DistributionElementType::class);
    }

    public static function smartMover(): callable
    {
        return self::get(SmartMoverType::class);
    }

    /**
     * @param class-string<Type> $classname
     *
     * @return Closure(): Type
     */
    private static function get(string $classname): Closure
    {
        return static fn () => self::byClassName($classname);
    }

    /**
     * @param class-string<Type> $classname
     */
    private static function byClassName(string $classname): Type
    {
        $parts = explode('\\', $classname);

        $withoutTypePrefix = preg_replace('~Type$~', '', $parts[count($parts) - 1]);
        assert(is_string($withoutTypePrefix), 'regex is statically known to be correct');

        $cacheName = strtolower($withoutTypePrefix);

        if (! isset(self::$types[$cacheName])) {
            return self::$types[$cacheName] = new $classname();
        }

        return self::$types[$cacheName];
    }

    public static function byTypeName(string $shortName): Type
    {
        $cacheName = strtolower($shortName);
        $type = null;

        if (isset(self::$types[$cacheName])) {
            return self::$types[$cacheName];
        }

        $method = lcfirst($shortName);
        if (method_exists(self::class, $method)) {
            $type = self::{$method}();
        }

        if (! $type) {
            throw new Exception('Unknown graphql type: ' . $shortName);
        }

        return $type;
    }

    public static function boolean(): ScalarType
    {
        return Type::boolean();
    }

    public static function float(): ScalarType
    {
        return Type::float();
    }

    public static function id(): ScalarType
    {
        return Type::id();
    }

    public static function int(): ScalarType
    {
        return Type::int();
    }

    public static function string(): ScalarType
    {
        return Type::string();
    }

    public static function node(): callable
    {
        return self::get(NodeType::class);
    }

    public static function listOf($type): ListOfType
    {
        return Type::listOf($type);
    }

    public static function story(): callable
    {
        return self::get(StoryType::class);
    }

    public static function comment(): callable
    {
        return self::get(CommentType::class);
    }

    public static function nftMetaData(): callable
    {
        return self::get(NftMetaDataType::class);
    }

    public static function nftMetaAttribute(): callable
    {
        return self::get(NftMetaAttributeType::class);
    }
}