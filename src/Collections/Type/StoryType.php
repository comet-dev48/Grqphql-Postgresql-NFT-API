<?php declare(strict_types=1);

namespace GraphQL\Collections\Type;

use GraphQL\Collections\AppContext;
use GraphQL\Collections\Data\DataSource;
use GraphQL\Collections\Data\Story;
use GraphQL\Collections\Data\Comment;
use GraphQL\Collections\Types;
use GraphQL\Type\Definition\ListOfType;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;

use function method_exists;
use function ucfirst;

class StoryType extends ObjectType
{
    public function __construct()
    {
        parent::__construct([
            'name' => 'Story',
            'fields' => static fn (): array => [
                'id' => Types::id(),
                'totalCommentCount' => Types::int(),
                'comments' => [
                    'type' => new ListOfType(Types::comment()),
                    'args' => [
                        'after' => [
                            'type' => Types::id(),
                            'description' => 'Load all comments listed after given comment ID',
                        ],
                        'limit' => [
                            'type' => Types::int(),
                            'defaultValue' => 5,
                        ],
                    ],
                ]
            ],
            'interfaces' => [Types::node()],
            'resolveField' => function (Story $story, array $args, $context, ResolveInfo $info) {
                $fieldName = $info->fieldName;

                $method = 'resolve' . ucfirst($fieldName);
                if (method_exists($this, $method)) {
                    return $this->{$method}($story, $args, $context, $info);
                }

                return $story->{$fieldName};
            },
        ]);
    }

    public function resolveTotalCommentCount(Story $story): int
    {
        return DataSource::countComments($story->id);
    }

    /**
     * @param array{limit: int, after?: string} $args
     *
     * @return array<int, Comment>
     */
    public function resolveComments(Story $story, array $args): array
    {
        return DataSource::findComments(
            $story->id,
            $args['limit'],
            isset($args['after'])
                ? (int) $args['after']
                : null
        );
    }
}