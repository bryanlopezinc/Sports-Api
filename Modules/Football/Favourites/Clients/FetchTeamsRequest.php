<?php

declare(strict_types=1);

namespace Module\Football\Favourites\Clients;

use Module\Football\DTO\Team;
use Illuminate\Support\Collection;
use Illuminate\Http\Client\Response;
use Illuminate\Pagination\Paginator;
use Module\Football\ValueObjects\TeamId;
use Module\Football\Services\CacheTeamService;
use Module\Football\Collections\TeamsCollection;
use Module\Football\Contracts\Cache\TeamsCacheInterface;
use Module\Football\Clients\ApiSports\V3\ApiSportsRequest;
use Module\Football\Collections\TeamIdsCollection;
use Module\Football\Favourites\Models\Favourite;
use Module\User\Favourites\Clients\RequestsFavouriteResourceInterface as RequestInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Client\Pool;
use Module\Football\Clients\ApiSports\V3\Response\TeamResponseJsonMapper;

/**
 * Prepare the team ids from user favourites (football) table for api request
 */
final class FetchTeamsRequest implements RequestInterface
{
    private TeamIdsCollection $requestedTeams;

    public function __construct(private TeamsCacheInterface $teamsCache, private CacheTeamService $cacheTeamService)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function configure(Pool $pool, Paginator $collection): array
    {
        return $collection->getCollection()
            ->filter(fn (Model $favourite) => $favourite['type'] === Favourite::TEAM_TYPE)
            ->map(fn (Model $favourite) => new TeamId($favourite['favourite_id']))
            ->tap(fn (Collection $collection) => $this->requestedTeams = new TeamIdsCollection($collection->all()))
            ->reject(fn (TeamId $teamId) => $this->teamsCache->has($teamId))
            ->map(function (TeamId $id) use ($pool) {
                $request = ApiSportsRequest::findTeamRequest($id);

                return $pool->as($this->key($id))->withHeaders($request->headers())->get($request->uri(), $request->query());
            })
            ->all();
    }

    public function key(TeamId $teamId = null): string
    {
        return 'fb:team:' . $teamId?->toInt();
    }

    /**
     * @param array<string, Response> $response
     */
    public function toDataTransferObject(array $response): array
    {
        return collect($response)
            ->filter(fn (Response $response, string $alias): bool => str_starts_with($alias, $this->key()))
            ->each(fn (Response $response) => $response->onError(fn () => abort(500)))
            ->map(fn (Response $response): array => $response->json('response.0'))
            ->map(new TeamResponseJsonMapper())
            ->tap(fn (Collection $collection) => $this->cacheTeamService->cacheMany(new TeamsCollection($collection->all())))
            ->pipe(fn (Collection $collection) => $this->teamsCache->getMany($this->requestedTeams)->merge($collection->all())->toArray());
    }
}
