<?php

declare(strict_types=1);

namespace Module\User\Favourites;

use App\ValueObjects\Uid;
use Module\User\ValueObjects\UserId;

final class ResourceAddedToFavouritesEvent
{
    public function __construct(
        public readonly UserId $userId,
        public readonly Uid $recordId
    ) {
    }
}
