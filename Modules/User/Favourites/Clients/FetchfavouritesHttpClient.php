<?php

declare(strict_types=1);

namespace Module\User\Favourites\Clients;

use App\Utils\PaginationData;
use Illuminate\Http\Client\Pool;
use Illuminate\Support\Collection;
use Illuminate\Http\Client\Response;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Http;
use Module\User\ValueObjects\UserId;
use Module\User\Favourites\FetchUserFavouritesResourcesResult as Result;
use Module\User\Favourites\FavouritesCollection;
use Module\User\Favourites\FavouritesRepository;
use Module\User\Favourites\FetchUserFavouritesResourcesInterface;

final class FetchfavouritesHttpClient implements FetchUserFavouritesResourcesInterface
{
    /** @var array<string> */
    private array $factories = [
        \Module\Football\Favourites\Clients\FetchLeaguestRequest::class,
        \Module\Football\Favourites\Clients\FetchTeamsRequest::class
    ];

    /** @var array<RequestInterface> */
    private array $factoryInstances;

    public function __construct(private FavouritesRepository $repository)
    {
    }

    public function fetchResources(UserId $userId, PaginationData $pagination): Result
    {
        $favourites = $this->repository->getFavourites($userId, $pagination);

        if ($favourites->isEmpty()) {
            return new Result(new FavouritesCollection([]), new Paginator([], $pagination->getPerPage()));
        }

        $this->setInstances();

        $response = $this->pool($this->getPendingRequestsFrom($favourites));

        $collection = collect($this->factoryInstances)
            ->map(fn (RequestInterface $factory): array => $factory->mapResponsesToDto($response))
            ->flatten()
            ->pipe(fn (Collection $collection) => new FavouritesCollection($collection->all()));

        return new Result($collection, $favourites);
    }

    private function setInstances(): void
    {
        $this->factoryInstances = array_map(fn (string $factory): RequestInterface => app($factory), $this->factories);
    }

    /**
     * @param array<string, Request> $requests
     *
     * @return array<string, Response>
     */
    private function pool(array $requests): array
    {
        $responses = Http::pool(function (Pool $pool) use ($requests) {
            $callback = function (Request $request, string|int $alias) use ($pool) {
                return $pool
                    ->as((string) $alias)
                    ->withHeaders($request->headers())
                    ->get($request->uri(), $request->query());
            };

            return collect($requests)->map($callback)->all();
        });

        foreach ($responses as $response) {
            $response->onError(fn (Response $response) => abort(500));
        }

        return $responses;
    }

    /**
     * @return array<string, Request>
     */
    private function getPendingRequestsFrom(Paginator $collection)
    {
        $pendingRequests = [];

        collect($this->factoryInstances)
            ->map(fn (RequestInterface $factory) => $factory->buildRequestObjectsWith($collection))
            ->each(function (array $requests) use (&$pendingRequests) {
                foreach ($requests as $alias => $request) {
                    $pendingRequests[$alias] = $request;
                }
            });

        return $pendingRequests;
    }
}
