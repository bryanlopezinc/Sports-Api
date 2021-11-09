<?php

declare(strict_types=1);

namespace Module\User\Clients\Favourites;

use Illuminate\Http\Client\Pool;
use Illuminate\Support\Collection;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Module\User\Collections\UserFavouritesCollection;
use Module\User\Collections\UserFavouriteTypesCollection;
use Module\User\Contracts\FetchUserFavouritesResourcesRepositoryInterface;

final class UserfavouritesHttpClient implements FetchUserFavouritesResourcesRepositoryInterface
{
    /**
     * The clients that will fetch the user favourites resources
     * Each client must implement the FetchFavouritesResourcesInterface.
     * @var array<string>
     */
    private array $clients = [
        FetchUserFavouriteFootballTeams::class,
        FetchUserFavouriteFootballLeagues::class
    ];

    /**
     * @param array<string> $clients
     */
    public function __construct(array $clients = [])
    {
        foreach ($clients as $client) {
            $this->clients[] = $client;
        }
    }

    public function fetch(UserFavouriteTypesCollection $collection): UserFavouritesCollection
    {
        $clientsInstances = collect($this->clients)->map(fn (string|Object $client): FetchFavouritesResourcesInterface => is_string($client) ? app($client) : $client);

        $response = $this->pool($this->getPendingRequestsFrom($clientsInstances->all(), $collection));

        return $clientsInstances
            ->map(fn (FetchFavouritesResourcesInterface $client): array => $client->mapResponsesToDto($response))
            ->flatten()
            ->pipe(fn (Collection $collection) => new UserFavouritesCollection($collection->all()));
    }

    /**
     * @param array<string, Request> $requests
     *
     * @return array<string, Response>
     */
    private function pool(array $requests): array
    {
        $responses = Http::pool(function (Pool $pool) use ($requests) {
            return collect($requests)
                ->map(function (Request $request, string|int $alias) use ($pool) {
                    return $pool
                        ->as((string) $alias)
                        ->withHeaders($request->headers())
                        ->get($request->uri(), $request->query());
                })->all();
        });

        foreach ($responses as $response) {
            $response->onError(fn (Response $response) => abort(500));
        }

        return $responses;
    }

    /**
     * @return array<string, Request>
     */
    private function getPendingRequestsFrom(array $clients, UserFavouriteTypesCollection $collection)
    {
        $pendingRequests = [];

        collect($clients)
            ->map(fn (FetchFavouritesResourcesInterface $client) => $client->getRequestObjectsFrom($collection))
            ->each(function (array $requests) use (&$pendingRequests) {
                foreach ($requests as $alias => $request) {
                    $pendingRequests[$alias] = $request;
                }
            });

        return $pendingRequests;
    }
}
