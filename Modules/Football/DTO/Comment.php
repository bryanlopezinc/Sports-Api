<?php

declare(strict_types=1);

namespace Module\Football\DTO;

use App\DTO\DataTransferObject;
use Module\User\Dto\User;
use App\ValueObjects\DateValue;
use App\ValueObjects\ResourceId;
use Module\Football\ValueObjects\FixtureId;
use Module\Football\ValueObjects\Comment as UserComment;

final class Comment extends DataTransferObject
{
    protected ResourceId $id;
    protected UserComment $userComment;
    protected User $commentedBy;
    protected FixtureId $fixtureId;
    protected DateValue $time;

    public function id(): ResourceId
    {
        return $this->id;
    }

    public function fixtureId(): FixtureId
    {
        return $this->fixtureId;
    }

    public function time(): DateValue
    {
        return $this->time;
    }

    public function commentedBy(): User
    {
        return $this->commentedBy;
    }

    public function userComment(): UserComment
    {
        return $this->userComment;
    }
}
