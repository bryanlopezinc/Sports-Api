<?php

declare(strict_types=1);

namespace Module\Football\Clients\ApiSports\V3;

use LogicException;
use App\Utils\TimeToLive;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Contracts\Cache\Repository;

final class FailedResponseCache
{
    private Repository $repository;

    public function __construct(Repository $repository = null)
    {
        $this->repository = $repository ?: Cache::store();
    }

    public function urlHasPreviousFailedResponse(string $uri): bool
    {
        return $this->repository->has($this->hashUri($uri));
    }

    public function getPreviousFailedResponseFor(string $uri): Response
    {
        return $this->repository->get($this->hashUri($uri));
    }

    private function hashUri(string $uri): string
    {
        return md5($uri);
    }

    public function cache(Response $errorResponse, TimeToLive $ttl): bool
    {
        if ($errorResponse->successful()) {
            throw new LogicException('Cannot cache successful response');
        }

        return $this->repository->put(
            $this->hashUri((string)$errorResponse->effectiveUri()),
            $errorResponse,
            $ttl->ttl()
        );
    }
}
