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
use Module\Football\Clients\ApiSports\V3\FetchTeamHttpClient;
use Module\Football\Clients\ApiSports\V3\Requests\FetchTeamByIdRequest;
use Module\Football\Collections\TeamIdsCollection;
use Module\Football\Favourites\Models\Favourite;
use Module\User\Favourites\Clients\RequestInterface;
use Illuminate\Database\Eloquent\Model;
use Module\User\Favourites\Clients\Request;

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
    public function buildRequestObjectsWith(Paginator $collection): array
    {
        $requests = [];

        $collection->getCollection()
            ->filter(fn (Model $favourite) => $favourite['type'] === Favourite::TEAM_TYPE)
            ->map(fn (Model $favourite) => new TeamId($favourite['favourite_id']))
            ->tap(fn (Collection $collection) => $this->requestedTeams = new TeamIdsCollection($collection->all()))
            ->reject(fn (TeamId $teamId) => $this->teamsCache->has($teamId))
            ->each(function (TeamId $id) use (&$requests) {
                $request = new FetchTeamByIdRequest($id);

                $requests[$this->key($id)] = new Request($request->uri(), $request->query(), $request->headers());
            });

        return $requests;
    }

    public function key(TeamId $teamId = null): string
    {
        return 'fb:team:' . $teamId?->toInt();
    }

    /**
     * {@inheritdoc}
     */
    public function mapResponsesToDto(array $response): array
    {
        $jsonResponseMapper = new FetchTeamHttpClient();

        return collect($response)
            ->filter(fn (Response $response, string $alias): bool => str_starts_with($alias, $this->key()))
            ->map(fn (Response $response): Team => $jsonResponseMapper->mapJsonResponseIntoTeamDto($response))
            ->tap(fn (Collection $collection) => $this->cacheTeamService->cacheMany(new TeamsCollection($collection->all())))
            ->pipe(fn (Collection $collection) => $this->teamsCache->getMany($this->requestedTeams)->merge($collection->all())->toArray());
    }
}
