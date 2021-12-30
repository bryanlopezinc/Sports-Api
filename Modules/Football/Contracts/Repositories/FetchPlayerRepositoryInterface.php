<?php

declare(strict_types=1);

namespace Module\Football\Contracts\Repositories;

use Module\Football\DTO\Player;
use Module\Football\ValueObjects\PlayerId;

interface FetchPlayerRepositoryInterface
{
    /**
     * The full data of the player is returned.
     *
     * @throws \App\Exceptions\Http\ResourceNotFoundHttpException
     */
    public function findById(PlayerId $id): Player;
}
