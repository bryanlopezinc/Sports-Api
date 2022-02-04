<?php

declare(strict_types=1);

namespace Module\Football\Http\Resources;

use Illuminate\Http\Request;
use Module\Football\DTO\Comment;
use Module\User\Http\Resources\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

final class CommentResource extends JsonResource
{
    public function __construct(private Comment $comment)
    {
        parent::__construct($comment);
    }

    /**
     * @param Request $request
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'type'       => 'football_fixture_comment',
            'attributes' => [
                'id'            => $this->comment->id->toInt(),
                'comment'       => $this->comment->userComment->value,
                'commented_by'  => new UserResource($this->comment->commentedBy),
                'date'          => $this->comment->time->toCarbon()->toDateTimeString(),
            ],
        ];
    }
}
