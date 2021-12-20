<?php

declare(strict_types=1);

namespace Module\Football\Clients\ApiSports\V3\Response;

use Carbon\Carbon;
use Module\Football\Clients\ApiSports\V3\CountryNameNormalizers\CountryNameNormalizerUsingSimilarText;
use Module\Football\DTO\Team;
use Module\Football\DTO\Venue;
use Module\Football\DTO\League;
use Module\Football\DTO\Fixture;
use Module\Football\DTO\Builders\TeamBuilder;
use Module\Football\DTO\Builders\VenueBuilder;
use Module\Football\DTO\Builders\LeagueBuilder;
use Module\Football\ValueObjects\FixtureStatus;
use Module\Football\DTO\Builders\FixtureBuilder;
use Module\Football\DTO\Builders\LeagueSeasonBuilder;

final class FixtureResponseJsonMapper
{
    private FixtureBuilder $builder;
    private VenueBuilder $venueBuilder;
    private LeagueBuilder $leagueBuilder;
    private Response $response;

    /**
     * @param array<string, mixed> $data
     */
    public function __construct(
        array $data,
        FixtureBuilder $builder = null,
        VenueBuilder $venueBuilder = null,
        private ?TeamBuilder $teamBilder = null,
        LeagueBuilder $leagueBuilder = null
    ) {

        $this->response = new Response($data);
        $this->builder = $builder ?: new FixtureBuilder;
        $this->venueBuilder = $venueBuilder ?: new VenueBuilder();
        $this->leagueBuilder = $leagueBuilder ?: new LeagueBuilder();
    }

    public function toDataTransferObject(): Fixture
    {
        $fixture = $this->builder
            ->setId($this->response->get('fixture.id'))
            ->setReferee($this->getReferee())
            ->setTimezone($this->response->get('fixture.timezone'))
            ->setDate(Carbon::parse($this->response->get('fixture.date'))->toDateTimeString())
            ->setFixtureStatus($this->convertFixtureStatus())
            ->setTimeElapsed($this->response->get('fixture.status.elapsed'))
            ->setVenueInfoIsAvailable($this->hasVenueDetails())
            ->when($this->hasVenueDetails(), fn (FixtureBuilder $b) => $b->setVenue($this->mapVenueResponseIntoDto()))
            ->setLeague($this->mapLeagueResponseIntoDto($this->response->get('league')))
            ->setHomeTeam($this->mapTeamResponseIntoDto($this->response->get('teams.home')))
            ->setAwayTeam($this->mapTeamResponseIntoDto($this->response->get('teams.away')))
            ->setWinnerId($this->determineWinnerId())
            ->setGoals($this->response->get('goals.home'), $this->response->get('goals.away'))
            ->setHalfTimeScore($this->response->get('score.halftime.home'), $this->response->get('score.halftime.away'))
            ->setFullTimeScore($this->response->get('score.fulltime.home'), $this->response->get('score.fulltime.away'))
            ->setExtraTimeScore($this->response->get('score.extratime.home'), $this->response->get('score.extratime.away'))
            ->setPenaltyScore($this->response->get('score.penalty.home'), $this->response->get('score.penalty.away'))
            ->build();

        return $this->confirmFixtureStatus($fixture);
    }

    private function confirmFixtureStatus(Fixture $fixture): Fixture
    {
        if (!$fixture->status()->isFullTime()) {
            return $fixture;
        }

        $secondPeriodStartTime = Carbon::createFromTimestamp($this->response->get('fixture.periods.second'));

        //Determine if fixture finished less than X minutes ago.
        //since no info about how many minutes added time was given in the second period
        //A slightly higher amount of minutes is used for total confirmation.
        $wasRecentlyConcluded = now()->diffInMinutes($secondPeriodStartTime->addMinutes(45)) < 25;

        if (!$wasRecentlyConcluded) {
            return $fixture;
        }

        // As there is no status indicating that a fixture is awaiting extra time
        //fixture status is set to confirming extra time to prevent declaring a fixture
        //100 percent concluded when fixture is awaiting extra time. This is to prevent
        //caching a fixture (marked as finished) for a long period of time and lead to stale data response
        return $this->builder
            ->fromFixture($fixture)
            ->setFixtureStatus(FixtureStatus::CONFIRMING_EXTRA_TIME)
            ->build();
    }

    private function convertFixtureStatus(): int
    {
        return match ($this->response->get('fixture.status.short')) {
            'TBD'   => FixtureStatus::TBD,
            'NS'    => FixtureStatus::NOT_STARTED,
            '1H'    => FixtureStatus::FIRST_HALF,
            'HT'    => FixtureStatus::HALF_TIME,
            '2H'    => FixtureStatus::SECOND_HALF,
            'ET'    => FixtureStatus::EXTRA_TIME,
            'P'     => FixtureStatus::PENALTY_IN_PROGRESS,
            'FT'    => FixtureStatus::FULL_TIME,
            'AET'   => FixtureStatus::FINISHED_AFTER_EXTRA_TIME,
            'PEN'   => FixtureStatus::FINISHED_AFTER_PENALTY,
            'BT'    => FixtureStatus::EXTRA_TIME_BREAK,
            'SUSP'  => FixtureStatus::SUSPENDED,
            'INT'   => FixtureStatus::INTERRUPTED,
            'PST'   => FixtureStatus::POSTPONED,
            'CANC'  => FixtureStatus::CANCELLED,
            'ABD'   => FixtureStatus::ABANDONED,
            'AWD'   => FixtureStatus::TECHNICAL_LOSS,
            'WO'    => FixtureStatus::WALK_OVER,
            'Live'  => FixtureStatus::NO_COVERAGE
        };
    }

    private function getReferee(): ?string
    {
        $refreeNameAndCountry = $this->response->get('fixture.referee');

        if ($refreeNameAndCountry === null) {
            return null;
        }

        return explode(',', $refreeNameAndCountry)[0];
    }

    /**
     * @param array<string, mixed> $data
     */
    private function mapTeamResponseIntoDto(array $data): Team
    {
        return (new TeamJsonMapper($data, $this->teamBilder))->toDataTransferObject();
    }

    /**
     * @param array<string, mixed> $data
     */
    public function mapLeagueResponseIntoDto(array $data): League
    {
        $data = new Response($data);

        return $this->leagueBuilder
            ->setId($data->get('id'))
            ->setName($data->get('name'))
            ->setCountry(new CountryNameNormalizerUsingSimilarText($data->get('country')))
            ->setLogoUrl($data->get('id'))
            ->setSeason((new LeagueSeasonBuilder())->setSeason($data->get('season'))->build())
            ->build();
    }

    private function mapVenueResponseIntoDto(): Venue
    {
        $response = new Response($this->response->get('fixture.venue'));

        return $this->venueBuilder
            ->setCity($response->get('city'))
            ->setName($response->get('name'))
            ->build();
    }

    private function hasVenueDetails(): bool
    {
        $response = new Response($this->response->get('fixture.venue'));

        return $response->get('city') !== null && $response->get('name') !== null;
    }

    private function determineWinnerId(): ?int
    {
        $teams = $this->response->get('teams');

        //only finished fixture should have a winner
        if (notInArray($this->response->get('fixture.status.short'), ['FT', 'AET', 'PEN', 'WO'])) {
            return null;
        }

        //Api response returns null for both teams in a draw outcome
        if ($teams['home']['winner'] === null && $teams['away']['winner'] == null) {
            return null;
        }

        return collect($teams)
            ->filter(fn (array $team): bool => $team['winner'] === true)
            ->map(fn (array $team) => $team['id'])
            ->sole();
    }
}
