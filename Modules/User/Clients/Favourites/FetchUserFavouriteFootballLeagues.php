<?php

declare(strict_types=1);

namespace Module\User\Clients\Favourites;

use Module\Football\DTO\League;
use Illuminate\Support\Collection;
use Illuminate\Http\Client\Response;
use Module\Football\ValueObjects\LeagueId;
use Module\Football\Services\CacheLeagueService;
use Module\Football\Collections\LeaguesCollection;
use Module\User\Collections\UserFavouriteTypesCollection;
use Module\Football\Contracts\Cache\LeaguesCacheInterface;
use Module\Football\Clients\ApiSports\V3\FetchLeagueHttpClient;
use Module\Football\Clients\ApiSports\V3\Requests\FetchLeagueByIdRequest;

final class FetchUserFavouriteFootballLeagues implements FetchFavouritesResourcesInterface
{
    private LeaguesCollection $leaguesAlreadyInCache;

    public function __construct(private LeaguesCacheInterface $cache)
    {
    }

    /**
     * @return array<Request>
     */
    public function getRequestObjectsFrom(UserFavouriteTypesCollection $collection): array
    {
        $this->leaguesAlreadyInCache = $existingLeagues = $this->cache->findManyById($collection->getLeagueTypeIds());

        /** @var array<LeagueId> */
        $idToRequest = $collection->getLeagueTypeIds()->except($existingLeagues->pluckIds())->toArray();

        $requests = [];

        foreach ($idToRequest as $id) {
            $request = new FetchLeagueByIdRequest($id);

            $requests['fb:league:' . $id->toInt()] = new Request($request->uri(), $request->query(), $request->headers());
        }

        return $requests;
    }

    /**
     * @param array<string, Response> $response
     */
    public function mapResponsesToDto(array $response): array
    {
        $jsonResponseMapper = new FetchLeagueHttpClient();

        $leagues =  collect($response)
            ->filter(fn (Response $response, string $alias): bool => str_starts_with($alias, 'fb:league:'))
            ->map(fn (Response $response): League => $jsonResponseMapper->mapJsonResponseIntoLeagueDto($response))
            ->tap(fn (Collection $collection) => $this->cacheLeagues($collection->all()))
            ->all();

        return $this->leaguesAlreadyInCache->merge($leagues)->toArray();
    }

    /**
     * @param array<League> $leagues
     */
    private function cacheLeagues(array $leagues): void
    {
        (new CacheLeagueService($this->cache))->cacheMany(new LeaguesCollection($leagues));
    }
}
