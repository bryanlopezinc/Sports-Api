<?php

declare(strict_types=1);

namespace Module\User\Http\Resources;

use App\Utils\PaginationData;
use Illuminate\Http\Request;
use Module\Football\DTO\Team;
use Module\Football\DTO\League;
use Module\User\UserFavouritesResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Arr;
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
            'links'         => $this->getPaginationLinks($request)
        ];
    }

    /**
     * @return array<mixed>
     */
    private function transformFavourites(): array
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

    private function getPaginationLinks(Request $request)
    {
        $options = [
            'path' => Paginator::resolveCurrentPath(),
            'query' => Paginator::resolveQueryString()
        ];

        $perPage = $request->input('per_page', PaginationData::PER_PAGE);

        $pagination = new Paginator($this->collection->favourites()->toLaravelCollection(), $perPage, options: $options);

        $pagination->hasMorePagesWhen($this->collection->hasMorePages());

        $links = $pagination->toArray();

        $links['next_page_url'] = $this->when($links['next_page_url'] !== null, $links['next_page_url']);
        $links['prev_page_url'] = $this->when($links['prev_page_url'] !== null, $links['prev_page_url']);
        $links['has_more_pages'] = $this->collection->hasMorePages();

        return Arr::except($links, ['data']);
    }
}
