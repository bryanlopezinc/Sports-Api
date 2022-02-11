<?php

declare(strict_types=1);

namespace Module\Football\Clients\ApiSports\V3\Response;

use Carbon\Carbon;
use Module\Football\Clients\ApiSports\V3\CountryNameNormalizers\CountryNameNormalizerUsingSimilarText;
use Module\Football\DTO\Team;
use Module\Football\DTO\League;
use Module\Football\DTO\Fixture;
use Module\Football\DTO\Builders\TeamBuilder;
use Module\Football\DTO\Builders\LeagueBuilder;
use Module\Football\ValueObjects\FixtureStatus;
use Module\Football\DTO\Builders\FixtureBuilder;
use Module\Football\DTO\Builders\LeagueSeasonBuilder;
use App\ValueObjects\NonEmptyString as VenueName;
use Module\Football\Venue;

final class FixtureResponseJsonMapper
{
    public function __construct(
        private FixtureBuilder $builder = new FixtureBuilder,
        private TeamBuilder $teamBilder = new TeamBuilder,
        private LeagueBuilder $leagueBuilder = new LeagueBuilder
    ) {
    }

    public function __invoke(array $data): Fixture
    {
        $response = new Response($data);

        $fixture = $this->builder
            ->setId($response->get('fixture.id'))
            ->setReferee($this->getReferee($data))
            ->setTimezone($response->get('fixture.timezone'))
            ->setDate(Carbon::parse($response->get('fixture.date'))->toDateTimeString())
            ->setFixtureStatus($this->convertFixtureStatus($data))
            ->setTimeElapsed($response->get('fixture.status.elapsed'))
            ->setVenue($this->getVenue($data))
            ->setLeague($this->mapLeagueResponseIntoDto($response->get('league')))
            ->setHomeTeam($this->mapTeamResponseIntoDto($response->get('teams.home')))
            ->setAwayTeam($this->mapTeamResponseIntoDto($response->get('teams.away')))
            ->setWinner($this->getWinner($data))
            ->setGoals($response->get('goals.home'), $response->get('goals.away'))
            ->setHalfTimeScore($response->get('score.halftime.home'), $response->get('score.halftime.away'))
            ->setFullTimeScore($response->get('score.fulltime.home'), $response->get('score.fulltime.away'))
            ->setExtraTimeScore($response->get('score.extratime.home'), $response->get('score.extratime.away'))
            ->setPenaltyScore($response->get('score.penalty.home'), $response->get('score.penalty.away'))
            ->build();

        return $this->confirmFixtureStatus($fixture, $data);
    }

    public function toDataTransferObject(array $response): Fixture
    {
        return $this($response);
    }

    private function confirmFixtureStatus(Fixture $fixture, array $data): Fixture
    {
        if (!$fixture->status()->isFullTime()) {
            return $fixture;
        }

        $secondPeriodStartTime = Carbon::createFromTimestamp($data['fixture']['periods']['second']);

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

    public function convertFixtureStatus(array $data): int
    {
        return match ($data['fixture']['status']['short']) {
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

    private function getReferee(array $data): ?string
    {
        $refreeNameAndCountry = $data['fixture']['referee'];

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

    private function getVenue(array $data): Venue
    {
        $city = $data['fixture']['venue']['city'];
        $name = $data['fixture']['venue']['name'];

        if (is_null($city) || is_null($name)) {
            return Venue::unknown();
        }

        return new Venue(new VenueName($name), $city);
    }

    public function getWinner(array $data): ?Team
    {
        $teams = $data['teams'];

        //only finished fixture should have a winner
        if (notInArray($data['fixture']['status']['short'], ['FT', 'AET', 'PEN', 'WO'])) {
            return null;
        }

        //Api response returns null for both teams in a draw outcome
        if ($teams['home']['winner'] === null && $teams['away']['winner'] == null) {
            return null;
        }

        return collect($teams)
            ->filter(fn (array $team): bool => $team['winner'] === true)
            ->map(fn (array $team) => $this->mapTeamResponseIntoDto($team))
            ->sole();
    }
}
