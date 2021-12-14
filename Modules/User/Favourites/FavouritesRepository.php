<?php

declare(strict_types=1);

namespace Module\User\Favourites;

use App\Utils\PaginationData;
use App\ValueObjects\ResourceId;
use Illuminate\Support\Collection;
use Module\User\ValueObjects\UserId;
use Illuminate\Database\QueryException;
use Module\User\Favourites\Dto\FavouriteBuilder;
use Module\User\Favourites\Exceptions\DuplicateEntryException;
use Module\User\Favourites\Models\FavouriteCount;
use Module\User\Favourites\Models\Favourite as Model;

final class FavouritesRepository
{
    /**
     * @throws DuplicateEntryException
     */
    public function create(UserId $userId, int $typeId, ResourceId $favouriteId): bool
    {
        try {
            Model::create([
                'user_id'       => $userId->toInt(),
                'type_id'       => $typeId,
                'favourite_id'  => $favouriteId->toInt()
            ]);
        } catch (QueryException $exception) {
            if ($exception->getCode() === '23000') {
                throw new DuplicateEntryException();
            }

            throw $exception;
        }

        $favouritesCount = FavouriteCount::where('user_id', $userId->toInt())->first();

        if ($favouritesCount === null) {
            FavouriteCount::create([
                'user_id'   => $userId->toInt(),
                'count'     => 1
            ]);

            return true;
        }

        $favouritesCount->increment('count');

        return true;
    }

    public function getFavourites(UserId $userId, PaginationData $pagination): PaginatedFavouritesCollection
    {
        /** @var \Illuminate\Pagination\Paginator */
        $result = Model::where('user_id', $userId->toInt())
            ->with('type')
            ->simplePaginate($pagination->getPerPage(), page: $pagination->getPage());

        return new PaginatedFavouritesCollection($this->mapFavouritesToDto($result->items()), $result);
    }

    /**
     * @param array<Model> $result
     */
    private function mapFavouritesToDto(array $result): FavouritesCollection
    {
        return collect($result)
            ->map(fn (Model $model) => FavouriteBuilder::fromModel($model)->build())
            ->pipe(fn (Collection $collection) => new FavouritesCollection($collection->all()));
    }
}
