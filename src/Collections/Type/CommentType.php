<?php declare(strict_types=1);

namespace GraphQL\Collections\Type;

use GraphQL\Collections\Data\Comment;
use GraphQL\Collections\Data\DataSource;
use GraphQL\Collections\Data\User;
use GraphQL\Collections\Types;
use GraphQL\Type\Definition\ListOfType;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;

use function method_exists;
use function ucfirst;

class CommentType extends ObjectType
{
    public function __construct()
    {
        parent::__construct([
            'name' => 'Comment',
            'fields' => static fn (): array => [
                'id' => Types::id(),
                'parent' => Types::comment(),
                'isAnonymous' => Types::boolean(),
                'replies' => [
                    'type' => new ListOfType(Types::comment()),
                    'args' => [
                        'after' => Types::int(),
                        'limit' => [
                            'type' => Types::int(),
                            'defaultValue' => 5,
                        ],
                    ],
                ],
                'totalReplyCount' => Types::int(),
            ],
            'resolveField' => function (Comment $comment, array $args, $context, ResolveInfo $info) {
                $fieldName = $info->fieldName;

                $method = 'resolve' . ucfirst($fieldName);
                if (method_exists($this, $method)) {
                    return $this->{$method}($comment, $args, $context, $info);
                }

                return $comment->{$fieldName};
            },
        ]);
    }

    public function resolveAuthor(Comment $comment): ?User
    {
        if ($comment->isAnonymous) {
            return null;
        }

        return DataSource::findUser($comment->authorId);
    }

    public function resolveParent(Comment $comment): ?Comment
    {
        if ($comment->parentId !== null) {
            return DataSource::findComment($comment->parentId);
        }

        return null;
    }

    /**
     * @param array{limit: int, after?: int} $args
     *
     * @return array<int, Comment>
     */
    public function resolveReplies(Comment $comment, array $args): array
    {
        $args += ['after' => null];

        return DataSource::findReplies($comment->id, $args['limit'], $args['after']);
    }

    public function resolveTotalReplyCount(Comment $comment): int
    {
        return DataSource::countReplies($comment->id);
    }
}