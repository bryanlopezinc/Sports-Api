<?php

declare(strict_types=1);

namespace Module\Football\Http\Resources;

use Illuminate\Http\Request;
use Module\Football\DTO\League;
use Module\Football\DTO\LeagueSeason;
use App\Http\Resources\CountryResource;
use Illuminate\Http\Resources\MissingValue;
use App\Utils\RescueInitializationException;
use Module\Football\Routes\FetchLeagueRoute;
use Illuminate\Http\Resources\Json\JsonResource;
use Module\Football\DTO\LeagueCoverage;
use Module\Football\Routes\FetchLeagueTopAssistsRoute;
use Module\Football\Routes\FetchLeagueTopScorersRoute;

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
                'id'                => $this->league->getId()->asHashedId(),
                'logo_url'          => $this->league->getLogoUrl()->toString(),
                'name'              => $this->league->getName()->value(),
                'country'           => $rescuer->rescue(fn () => new CountryResource($this->league->getCountry())),
                'season'            => $rescuer->rescue(fn () => $this->transformLeagueSeason($this->league->getSeason())),
            ],
            'links'            => [
                'self'          => new FetchLeagueRoute($this->league->getId()),
                'top_scorers'   => $this->topScorersLink(),
                'top_assists'   => $this->topAssistsLink()
            ]
        ];
    }

    private function topScorersLink(): MissingValue|FetchLeagueTopScorersRoute
    {
        /** @var LeagueCoverage|false */
        $season = (new RescueInitializationException(false))->rescue(fn () => $this->league->getSeason()->getCoverage());

        if ($season === false) {
            return new MissingValue;
        }

        if (!$season->coversTopScorers()) {
            return new MissingValue;
        }

        return new FetchLeagueTopScorersRoute($this->league->getId(), $this->league->getSeason()->getSeason());
    }

    private function topAssistsLink(): MissingValue|FetchLeagueTopAssistsRoute
    {
        /** @var LeagueCoverage|false */
        $season = (new RescueInitializationException(false))->rescue(fn () => $this->league->getSeason()->getCoverage());

        if ($season === false) {
            return new MissingValue;
        }

        if (!$season->coversTopAssists()) {
            return new MissingValue;
        }

        return new FetchLeagueTopAssistsRoute($this->league->getId(), $this->league->getSeason()->getSeason());
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
                'line_up'           => $leagueSeason->getCoverage()->coverslineUp(),
                'events'            => $leagueSeason->getCoverage()->coversEvents(),
                'stats'             => $leagueSeason->getCoverage()->coversStatistics(),
                'top_scorers'       => $leagueSeason->getCoverage()->coversTopScorers(),
                'top_assists'       => $leagueSeason->getCoverage()->coversTopAssists()
            ]
        ];
    }
}
