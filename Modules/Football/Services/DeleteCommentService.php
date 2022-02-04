<?php

declare(strict_types=1);

namespace Module\Football\Services;

use App\ValueObjects\ResourceId;
use Module\Football\Model\Comment;
use Module\User\ValueObjects\UserId;

final class DeleteCommentService
{
    /**
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function __invoke(ResourceId $commentId): void
    {
        $comment = Comment::query()->where('id', $commentId->toInt())->first();

        if (is_null($comment)) {
            return;
        }

        $commentBelongsToUser = UserId::fromAuthUser()->toInt() === $comment->commented_by_id;

        abort_if(!$commentBelongsToUser, 403);

        $comment->delete();
    }
}
