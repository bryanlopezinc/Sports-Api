<?php

declare(strict_types=1);

namespace Module\User\Favourites;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Module\Football\DTO\Fixture;
use Module\Football\Http\Resources\FixtureResource;

final class UserFavouriteFixturesResource extends JsonResource
{
    /**
     * @param array<Fixture> $collection
     */
    public function __construct(private array $collection)
    {
        parent::__construct($collection);
    }

    /**
     * @param Request $request
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return array_map(function ($fixture): JsonResource {
            return match ($fixture::class) {
                Fixture::class => new FixtureResource($fixture)
            };
        }, $this->collection);
    }
}
