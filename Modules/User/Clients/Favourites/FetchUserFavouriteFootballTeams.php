<?php

declare(strict_types=1);

namespace Module\User\Clients\Favourites;

use Module\Football\DTO\Team;
use Illuminate\Support\Collection;
use Illuminate\Http\Client\Response;
use Module\Football\ValueObjects\TeamId;
use Module\Football\Services\CacheTeamService;
use Module\Football\Collections\TeamsCollection;
use Module\Football\Contracts\Cache\TeamsCacheInterface;
use Module\User\Collections\UserFavouriteTypesCollection;
use Module\Football\Clients\ApiSports\V3\FetchTeamHttpClient;
use Module\Football\Clients\ApiSports\V3\Requests\FetchTeamByIdRequest;

final class FetchUserFavouriteFootballTeams implements FetchFavouritesResourcesInterface
{
    private TeamsCollection $teamsAlreadyInCache;

    public function __construct(private TeamsCacheInterface $teamsCache)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getRequestObjectsFrom(UserFavouriteTypesCollection $collection): array
    {
        $this->teamsAlreadyInCache = $existingTeams = $this->teamsCache->getMany($collection->getFootballTeamTypeIds());

        /** @var array<TeamId> */
        $idToRequest = $collection->getFootballTeamTypeIds()->except($existingTeams->pluckIds())->toArray();

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

        return $this->teamsAlreadyInCache->merge($teams)->toArray();
    }

    /**
     * @param array<Team> $teams
     */
    private function cacheTeams(array $teams): void
    {
        (new CacheTeamService($this->teamsCache))->cacheMany(new TeamsCollection($teams));
    }
}
