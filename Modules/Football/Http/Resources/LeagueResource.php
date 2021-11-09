<?php

declare(strict_types=1);

namespace Module\Football\Http\Resources;

use Illuminate\Http\Request;
use Module\Football\DTO\League;
use Module\Football\DTO\LeagueSeason;
use Illuminate\Http\Resources\MissingValue;
use App\Utils\RescueInitializationException;
use Module\Football\Routes\FetchLeagueRoute;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\CountryResource;

final class LeagueResource extends JsonResource
{
    public function __construct(private League $league)
    {
        parent::__construct($league);
    }

    /**
     * @param Request $request
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        $rescuer = new RescueInitializationException(new MissingValue);

        return [
            'type'              => 'football_league',
            'attributes'        => [
                'id'                => $this->league->getId()->toInt(),
                'logo_url'          => $this->league->getLogoUrl()->url(),
                'name'              => $this->league->getName(),
                'country'           => $rescuer->rescue(fn () => new CountryResource($this->league->getCountry())),
                'season'            => $rescuer->rescue(fn () => $this->transformLeagueSeason($this->league->getSeason())),
            ],
            'links'            => [
                'self'  => new FetchLeagueRoute($this->league->getId())
            ]
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function transformLeagueSeason(LeagueSeason $leagueSeason): array
    {
        return [
            'season'            => $leagueSeason->getSeason()->toInt(),
            'start'             => $leagueSeason->getDuration()->startDate()->toCarbon()->toDateString(),
            'end'               => $leagueSeason->getDuration()->endDate()->toCarbon()->toDateString(),
            'is_current_season' => $leagueSeason->isCurrentSeason(),
            'coverage'  => [
                'line_up'           => $leagueSeason->getCovergae()->coverslineUp(),
                'events'            => $leagueSeason->getCovergae()->coversEvents(),
                'stats'             => $leagueSeason->getCovergae()->coversStatistics(),
            ],
        ];
    }
}
