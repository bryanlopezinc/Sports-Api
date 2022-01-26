<?php

declare(strict_types=1);

namespace Module\Football\Favourites\Clients;

use Module\Football\DTO\League;
use Illuminate\Support\Collection;
use Illuminate\Http\Client\Response;
use Illuminate\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Client\Pool;
use Module\Football\ValueObjects\LeagueId;
use Module\Football\Favourites\Models\Favourite;
use Module\Football\Services\CacheLeagueService;
use Module\Football\Collections\LeaguesCollection;
use Module\Football\Collections\LeagueIdsCollection;
use Module\User\Favourites\Clients\RequestsFavouriteResourceInterface as RequestInterface;
use Module\Football\Contracts\Cache\LeaguesCacheInterface;
use Module\Football\Clients\ApiSports\V3\FetchLeagueHttpClient;
use Module\Football\Clients\ApiSports\V3\Requests\FetchLeagueByIdRequest;

/**
 * Prepare the league ids from user favourites (football) table for api request
 */
final class FetchLeaguestRequest implements RequestInterface
{
    private LeagueIdsCollection $requestedIds;

    public function __construct(private LeaguesCacheInterface $cache, private CacheLeagueService $cacheLeagueService)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function configure(Pool $pool, Paginator $collection): array
    {
        return $collection->getCollection()
            ->filter(fn (Model $favourite) => $favourite['type'] === Favourite::LEAGUE_TYPE)
            ->map(fn (Model $favourite) => new LeagueId($favourite['favourite_id']))
            ->tap(fn (Collection $collection) => $this->requestedIds = new LeagueIdsCollection($collection->all()))
            ->reject(fn (LeagueId $leagueId) => $this->cache->has($leagueId))
            ->map(function (LeagueId $id) use ($pool) {
                $endpoint = new FetchLeagueByIdRequest($id);

                return $pool->as($this->key($id))->withHeaders($endpoint->headers())->get($endpoint->uri(), $endpoint->query());
            })
            ->all();
    }

    public function key(LeagueId $leagueId = null): string
    {
        return 'fb:league:' . $leagueId?->toInt();
    }

    /**
     * @param array<string, Response> $response
     */
    public function toDataTransferObject(array $response): array
    {
        $jsonResponseMapper = new FetchLeagueHttpClient();

        return collect($response)
            ->filter(fn (Response $response, string $alias): bool => str_starts_with($alias, $this->key()))
            ->each(fn (Response $response) => $response->onError(fn () => abort(500)))
            ->map(fn (Response $response): League => $jsonResponseMapper->mapJsonResponseIntoLeagueDto($response))
            ->tap(fn (Collection $collection) => $this->cacheLeagueService->cacheMany(new LeaguesCollection($collection->all())))
            ->pipe(fn (Collection $collection) => $this->cache->findManyById($this->requestedIds)->merge($collection->all())->toArray());
    }
}
