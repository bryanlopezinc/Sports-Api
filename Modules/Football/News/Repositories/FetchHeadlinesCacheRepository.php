<?php

declare(strict_types=1);

namespace Module\Football\News\Repositories;

use Illuminate\Contracts\Cache\Repository;
use Module\Football\News\Contracts\FetchHeadlinesRepositoryInterface;

final class FetchHeadlinesCacheRepository implements FetchHeadlinesRepositoryInterface
{
     public function __construct(private Repository $cache, private FetchHeadlinesRepositoryInterface $repository)
     {
     }

    public function headlines(): array
    {
        return $this->cache->remember('Football-news-headlines', now()->addHour(), function(){
            return $this->repository->headlines();
        });
    }
}
