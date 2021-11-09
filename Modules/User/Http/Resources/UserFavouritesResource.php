<?php

declare(strict_types=1);

namespace Module\User\Http\Resources;

use Illuminate\Http\Request;
use Module\Football\DTO\Team;
use Module\Football\DTO\League;
use Module\User\UserFavouritesResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Module\Football\Http\Resources\TeamResource;
use Module\Football\Http\Resources\LeagueResource;

final class UserFavouritesResource extends JsonResource
{
    public function __construct(private UserFavouritesResponse $collection)
    {
        parent::__construct($collection);
    }

    /**
     * @param Request $request
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'type'          => 'user_favourites',
            'favourites'    => $this->transformFavourites(),
            'links'         => [

            ]
        ];
    }

    /**
     * @return array<mixed>
     */
    public function transformFavourites(): array
    {
        return $this->collection
            ->favourites()
            ->toLaravelCollection()
            ->map(function (Object $favourite) {
                return match ($favourite::class) {
                    Team::class     => new TeamResource($favourite),
                    League::class   => new LeagueResource($favourite)
                };
            })
            ->all();
    }
}
