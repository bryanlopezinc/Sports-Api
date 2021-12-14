<?php

declare(strict_types=1);

namespace Module\User\Favourites\Clients;

use App\Utils\PaginationData;
use Illuminate\Http\Client\Pool;
use Illuminate\Support\Collection;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Module\User\ValueObjects\UserId;
use Module\User\Favourites\FavouritesResponse;
use Module\User\Favourites\ResourcesCollection;
use Module\User\Favourites\FavouritesCollection;
use Module\User\Favourites\FavouritesRepository;
use Module\User\Favourites\FetchUserFavouritesResourcesInterface;

final class FetchfavouritesHttpClient implements FetchUserFavouritesResourcesInterface
{
    /**
     * @var array<string>
     */
    private array $resolvers = [
        FetchFootballTeams::class,
        FetchFootballLeagues::class
    ];

    public function __construct(private FavouritesRepository $repository)
    {
    }

    public function fetchResources(UserId $userId, PaginationData $pagination): FavouritesResponse
    {
        $favourites = $this->repository->getFavourites($userId, $pagination);

        $resolvers = collect($this->resolvers)->map(fn (string $resolver): FavouritesResolverInterface => app($resolver));

        $response = $this->pool($this->getPendingRequestsFrom($resolvers->all(), $favourites->getCollection()));

        $collection = $resolvers
            ->map(fn (FavouritesResolverInterface $client): array => $client->mapResponsesToDto($response))
            ->flatten()
            ->pipe(fn (Collection $collection) => new ResourcesCollection($collection->all()));

        return new FavouritesResponse($collection, $favourites->getPagination()->hasMorePages());
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
    private function getPendingRequestsFrom(array $resolvers, FavouritesCollection $collection)
    {
        $pendingRequests = [];

        collect($resolvers)
            ->map(fn (FavouritesResolverInterface $resolver) => $resolver->getRequestObjectsFrom($collection))
            ->each(function (array $requests) use (&$pendingRequests) {
                foreach ($requests as $alias => $request) {
                    $pendingRequests[$alias] = $request;
                }
            });

        return $pendingRequests;
    }
}
