<?php

declare(strict_types=1);

namespace Module\Football\Clients\ApiSports\V3\Response;

use Illuminate\Support\Collection;
use Module\Football\Clients\ApiSports\V3\CountryNameNormalizers\CountryNameNormalizerUsingSimilarText;
use Module\Football\ValueObjects\Season;
use Module\Football\ValueObjects\LeagueType;
use Module\Football\Collections\LeagueSeasonsCollection;
use Module\Football\DTO\{League, LeagueSeason, LeagueCoverage};
use Module\Football\DTO\Builders\{LeagueSeasonBuilder, LeagueCoverageBuilder, LeagueBuilder};

final class LeagueResponseJsonMapper extends Response
{
    private LeagueBuilder $builder;
    private LeagueCoverageBuilder $leagueCoverageBuilder;
    private LeagueSeasonBuilder $leagueSeasonBuilder;

    /**
     * @param array<string, mixed> $response
     */
    public function __construct(
        array $response,
        LeagueBuilder $builder = null,
        LeagueCoverageBuilder $leagueCoverageBuilder = null,
        LeagueSeasonBuilder $leagueSeasonBuilder = null,
    ) {
        parent::__construct($response);

        $this->builder = $builder ?: new LeagueBuilder;
        $this->leagueSeasonBuilder = $leagueSeasonBuilder ?: new LeagueSeasonBuilder;
        $this->leagueCoverageBuilder = $leagueCoverageBuilder ?: new LeagueCoverageBuilder;
    }

    public function tooDataTransferObject(Season $withSeason = null): League
    {
        return $this->builder
            ->setId($this->get('league.id'))
            ->setName($this->get('league.name'))
            ->setLogoUrl($this->get('league.logo'))
            ->when($this->has('seasons'), fn (LeagueBuilder $b) => $b->setSeason($this->getCorrespondingLeagueSeason($withSeason)))
            ->when($this->has('country.name'), fn (LeagueBuilder $b) => $b->setCountry(new CountryNameNormalizerUsingSimilarText($this->get('country.name'))))
            ->when($this->has('league.type'), function (LeagueBuilder $b) {
                return $b->setType(match ($this->get('league.type')) {
                    'League'    => LeagueType::LEAGUE,
                    'Cup'       => LeagueType::CUP
                });
            })
            ->build();
    }

    private function getCorrespondingLeagueSeason(Season $season = null): LeagueSeason
    {
        $seasons = $this->mapLeagueSeasons();

        //if a particular season was requested return corresponding season
        if ($season) {
            return $seasons->whereEquals($season);
        }

        //if any season is the current season return it as the league season
        if ($seasons->anySeasonIsCurrent()) {
            return $seasons->currentSeason();
        }

        // Return the recently concluded season as the league season
        //if no particular season was requested and no season is current.
        return $seasons->mostRecentSeason();
    }

    private function mapLeagueSeasons(): LeagueSeasonsCollection
    {
        return  collect($this->get('seasons'))
            ->map(function (array $season): LeagueSeason {
                $response = new Response($season);

                return $this->leagueSeasonBuilder
                    ->setSeason($response->get('year'))
                    ->setDuration($response->get('start'), $response->get('end'))
                    ->setIsCurrentSeason($response->get('current'))
                    ->setCoverage($this->mapToLeagueCoverageDataTransferObject($response->get('coverage')))
                    ->build();
            })
            ->pipe(fn (Collection $collection) => new LeagueSeasonsCollection($collection->all()));
    }

    /**
     * @param array<string, mixed> $coverageResponse
     */
    private function mapToLeagueCoverageDataTransferObject(array $coverageResponse): LeagueCoverage
    {
        $response = new Response($coverageResponse);

        return $this->leagueCoverageBuilder
            ->setSupportsStatistics($response->get('fixtures.statistics_fixtures'))
            ->supportsLineUp($response->get('fixtures.lineups'))
            ->supportsTopScorers($response->get('top_scorers'))
            ->supportsEvents($response->get('fixtures.events'))
            ->supportsTopAssists($response->get('top_assists'))
            ->build();
    }
}
