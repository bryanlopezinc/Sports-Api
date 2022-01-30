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
    public readonly ResourceId $id;
    public readonly UserComment $userComment;
    public readonly User $commentedBy;
    public readonly FixtureId $fixtureId;
    public readonly DateValue $time;
}
