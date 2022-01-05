<?php

declare(strict_types=1);

namespace Module\User\Favourites;

use App\Utils\PaginationData;
use App\ValueObjects\ResourceId;
use Illuminate\Support\Collection;
use Module\User\ValueObjects\UserId;
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
        $attributes = [
            'user_id'       => $userId->toInt(),
            'type_id'       => $typeId,
            'favourite_id'  => $favouriteId->toInt()
        ];

        if (Model::query()->where($attributes)->exists()) {
            throw new DuplicateEntryException();
        }

        Model::query()->create($attributes);

        $this->incrementFavouritesCount($userId);

        return true;
    }

    private function incrementFavouritesCount(UserId $userId): void
    {
        $favouritesCount = FavouriteCount::where('user_id', $userId->toInt())->first();

        if ($favouritesCount === null) {
            FavouriteCount::create([
                'user_id' => $userId->toInt(),
                'count'   => 1
            ]);

            return;
        }

        $favouritesCount->increment('count');
    }

    public function getFavourites(UserId $userId, PaginationData $pagination): PaginatedFavouritesCollection
    {
        /** @var \Illuminate\Pagination\Paginator */
        $result = Model::query()->where('user_id', $userId->toInt())
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
