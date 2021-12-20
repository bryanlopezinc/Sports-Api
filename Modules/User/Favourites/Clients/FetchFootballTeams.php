<?php

declare(strict_types=1);

namespace Module\User\Favourites\Clients;

use Module\Football\DTO\Team;
use Illuminate\Support\Collection;
use Illuminate\Http\Client\Response;
use Module\Football\ValueObjects\TeamId;
use Module\Football\Services\CacheTeamService;
use Module\Football\Collections\TeamsCollection;
use Module\User\Favourites\FavouritesCollection;
use Module\Football\Contracts\Cache\TeamsCacheInterface;
use Module\Football\Clients\ApiSports\V3\FetchTeamHttpClient;
use Module\Football\Clients\ApiSports\V3\Requests\FetchTeamByIdRequest;

final class FetchFootballTeams implements FavouritesResolverInterface
{
    private TeamsCollection $teamsInCache;

    public function __construct(private TeamsCacheInterface $teamsCache)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getRequestObjectsFrom(FavouritesCollection $collection): array
    {
        $this->teamsInCache = $this->teamsCache->getMany($collection->whereSportTypeIsfootball()->getTeamIds());

        /** @var array<TeamId> */
        $idToRequest = $collection->whereSportTypeIsfootball()->getTeamIds()->except($this->teamsInCache->pluckIds())->toArray();

        $requests = [];

        foreach ($idToRequest as $id) {
            $request = new FetchTeamByIdRequest($id);

            $requests['fb:team:' . $id->toInt()] = new Request($request->uri(), $request->query(), $request->headers());
        }

        return $requests;
    }

    /**
     * {@inheritdoc}
     */
    public function mapResponsesToDto(array $response): array
    {
        $jsonResponseMapper = new FetchTeamHttpClient();

        $teams =  collect($response)
            ->filter(fn (Response $response, string $alias): bool => str_starts_with($alias, 'fb:team:'))
            ->map(fn (Response $response): Team => $jsonResponseMapper->mapJsonResponseIntoTeamDto($response))
            ->tap(fn (Collection $collection) => $this->cacheTeams($collection->all()))
            ->all();

        return $this->teamsInCache->merge($teams)->toArray();
    }

    /**
     * @param array<Team> $teams
     */
    private function cacheTeams(array $teams): void
    {
        (new CacheTeamService($this->teamsCache))->cacheMany(new TeamsCollection($teams));
    }
}