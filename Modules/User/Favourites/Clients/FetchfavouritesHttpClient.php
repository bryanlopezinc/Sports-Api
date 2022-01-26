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
    private const CLIENTS = [
        \Module\Football\Favourites\Clients\FetchLeaguestRequest::class,
        \Module\Football\Favourites\Clients\FetchTeamsRequest::class
    ];

    public function __construct(private FavouritesRepository $repository)
    {
    }

    public function fetchResources(UserId $userId, PaginationData $pagination): Result
    {
        $favourites = $this->repository->getFavourites($userId, $pagination);

        if ($favourites->isEmpty()) {
            return new Result(new FavouritesCollection([]), new Paginator([], $pagination->getPerPage()));
        }

        $instances = array_map(fn (string $client): RequestsFavouriteResourceInterface => app($client), self::CLIENTS);

        $response = $this->pool($instances, $favourites);

        $collection = collect($instances)
            ->map(fn (RequestsFavouriteResourceInterface $client): array => $client->toDataTransferObject($response))
            ->flatten()
            ->pipe(fn (Collection $collection) => new FavouritesCollection($collection->all()));

        return new Result($collection, $favourites);
    }

    /**
     * @param array<RequestsFavouriteResourceInterface> $clients
     *
     * @return array<string|int, Response>
     */
    private function pool(array $clients, Paginator $favourites): array
    {
        return Http::pool(function (Pool $pool) use ($clients, $favourites) {
            return collect($clients)->map(fn (RequestsFavouriteResourceInterface $client): array => $client->configure($pool, $favourites))->flatten()->all();
        });
    }
}
