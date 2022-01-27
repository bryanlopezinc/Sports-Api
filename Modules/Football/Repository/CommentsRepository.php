<?php

declare(strict_types=1);

namespace Module\Football\Repository;

use Module\User\ValueObjects\UserId;
use Module\Football\ValueObjects\Comment;
use Module\Football\Model\Comment as Model;
use Module\Football\ValueObjects\FixtureId;

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
}
