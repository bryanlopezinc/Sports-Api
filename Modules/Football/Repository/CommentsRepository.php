<?php

declare(strict_types=1);

namespace Module\Football\Repository;

use App\Utils\PaginationData;
use Illuminate\Pagination\Paginator;
use Module\User\ValueObjects\UserId;
use Module\Football\ValueObjects\Comment;
use Module\Football\Model\Comment as Model;
use Module\Football\ValueObjects\FixtureId;
use Module\Football\DTO\Comment as CommentDTO;
use Module\Football\DTO\Builders\CommentBuilder;

final class CommentsRepository
{
    public const COMMENTABLE_TYPE = 'S1';

    public function saveFixtureComment(FixtureId $fixtureId, Comment $comment, UserId $userId): bool
    {
        Model::query()->create([
            'commentable_type' => self::COMMENTABLE_TYPE,
            'comment'          => $comment->value,
            'commentable_id'   => $fixtureId->toInt(),
            'commented_by_id'  => $userId->toInt()
        ]);

        return true;
    }

    /**
     * Only username, id, name and is_private attributes are returned for each user
     *
     * @return Paginator<CommentDTO>
     */
    public function getFixtureComments(FixtureId $fixtureId, PaginationData $pagination): Paginator
    {
        $column = [
            'commentable_type' => self::COMMENTABLE_TYPE,
            'commentable_id'   => $fixtureId->toInt(),
        ];

        /** @var Paginator */
        $comments = Model::query()
            ->with('user', fn ($builder) => $builder->select(['id', 'name', 'username', 'is_private']))
            ->where($column)
            ->simplePaginate($pagination->getPerPage(), page: $pagination->getPage());

        $comments->setCollection(
            $comments->getCollection()->map(fn (Model $comment): CommentDTO => CommentBuilder::fromModel($comment)->build())
        );

        return $comments;
    }
}
