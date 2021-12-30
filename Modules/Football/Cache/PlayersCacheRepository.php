<?php

declare(strict_types=1);

namespace Module\Football\Cache;

use Module\Football\DTO\Player;
use Illuminate\Contracts\Cache\Repository;
use Module\Football\ValueObjects\PlayerId;
use Module\Football\Contracts\Repositories\FetchPlayerRepositoryInterface;

final class PlayersCacheRepository implements FetchPlayerRepositoryInterface
{
    public function __construct(private Repository $repository, private FetchPlayerRepositoryInterface $fetchPlayerRepository)
    {
    }

    public function findById(PlayerId $id): Player
    {
        $key = new CachePrefix($this) . $id->toInt();

        return $this->repository->remember($key, now()->addWeek(), fn () => $this->fetchPlayerRepository->findById($id));
    }
}
