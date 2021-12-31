<?php

declare(strict_types=1);

namespace Module\Football\Cache;

use Illuminate\Contracts\Cache\Repository;
use Module\Football\PlayerTransferHistory;
use Module\Football\ValueObjects\PlayerId;
use Module\Football\Contracts\Repositories\FetchPlayerTransferHistoryRepositoryInterface;

final class PlayersTransferHistoryCacheRepository implements FetchPlayerTransferHistoryRepositoryInterface
{
    public function __construct(private Repository $cache, private FetchPlayerTransferHistoryRepositoryInterface $repository)
    {
    }

    public function forPlayer(PlayerId $id): PlayerTransferHistory
    {
        $key = new CachePrefix($this) . $id->toInt();

        return $this->cache->remember($key, now()->addWeek(), fn () => $this->repository->forPlayer($id));
    }
}
