<?php

declare(strict_types=1);

namespace Module\Football\Http\Resources;

use Illuminate\Http\Request;
use Module\Football\DTO\Fixture;
use Module\Football\FixturePeriodGoals;
use Module\Football\ValueObjects\TimeZone;
use Illuminate\Http\Resources\MissingValue;
use App\Utils\RescueInitializationException;
use Module\Football\Routes\FetchFixtureRoute;
use Module\Football\ValueObjects\FixtureStatus;
use Illuminate\Http\Resources\Json\JsonResource;
use Module\Football\Collections\FixturesCollection;
use Module\Football\Routes\FetchFixtureEventsRoute;
use Module\Football\Routes\FetchFixtureLineUpRoute;
use Module\Football\Routes\FetchFixtureStatisticsRoute;

final class FixtureResource extends JsonResource
{
    public function __construct(private Fixture $fixture)
    {
        parent::__construct($fixture);
    }

    /**
     * @param Request $request
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        $timezone = TimeZone::fromRequest($request, 'timezone');

        $rescuer = new RescueInitializationException(new MissingValue);

        $coverage = fn () => $this->fixture->league()->getSeason()->getCoverage();

        return [
            'type'              => 'football_fixture',
            'attributes'        => [
                'id'                   => $this->fixture->id()->toInt(),
                'referee'              => $this->transformRefereeData(),
                'date'                 => $this->fixture->date()->toCarbon($timezone->toDateTimeZone())->toDateTimeString(),
                'has_venue_info'       => $venueIsAvailable = $this->fixture->venueInfoIsAvailable(),
                'venue'                => $this->when($venueIsAvailable, fn () => new VenueResource($this->fixture->venue())),
                'minutes_elapsed'      => $this->when($this->fixture->timeElapsed() !== null, fn () => $this->fixture->timeElapsed()->minutes()),
                'status'               => $this->transFormFixtureStatus(),
                'league'               => new LeagueResource($this->fixture->league()),
                'has_winner'           => $this->fixture->hasWinner(),
                'winner'               => $this->when($this->fixture->hasWinner(), fn () => $this->transformMatchWinner()),
                'teams'                => [
                    'home'  => new TeamResource($this->fixture->getHomeTeam()),
                    'away'  => new TeamResource($this->fixture->getAwayTeam())
                ],
                'score_is_available'    => $this->fixture->scoreIsAvailable(),
                'score'                 => $this->when($this->fixture->scoreIsAvailable(), fn () => $this->transformFixtureScore()),
                'period_goals'          => $this->getPeriodGoalsData(),
            ],
            'links'     => [
                'self'      => (string) new FetchFixtureRoute($this->fixture->id()),
                'events'    => $rescuer->rescue(fn () => $this->when($coverage()->coversEvents(), new FetchFixtureEventsRoute($this->fixture->id()))),
                'line_up'   => $rescuer->rescue(fn () => $this->when($coverage()->coverslineUp(), new FetchFixtureLineUpRoute($this->fixture->id()))),
                'stats'     => $rescuer->rescue(fn () => $this->when($coverage()->coversStatistics(), new FetchFixtureStatisticsRoute($this->fixture->id()))),
            ]
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function transformRefereeData(): array
    {
        return [
            'name_is_availbale'  => $this->fixture->referee()->nameIsAvailable(),
            'name'               => $this->when($this->fixture->referee()->nameIsAvailable(), $this->fixture->referee()->name()),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function getPeriodGoalsData(): array
    {
        return [
            'meta'        => [
                'has_first_half_score'   => $hasFirstPeriodScore   = $this->fixture->hasFirstPeriodScore(),
                'has_full_time_score'    => $hasSecondPeriodScore = $this->fixture->hasSecondPeriodScore(),
                'has_extra_time_score'   => $hasExtraTimeScore    = $this->fixture->hasExtraPeriodScore(),
                'has_penalty_score'      => $hasPenaltyScore      = $this->fixture->hasPenaltyPeriodScore()
            ],
            'first_half'  => $this->when($hasFirstPeriodScore, fn () => $this->transformPeriodScore($this->fixture->getFirstPeriodScore())),
            'second_half' => $this->when($hasSecondPeriodScore, fn () => $this->transformPeriodScore($this->fixture->getSecondPeriodScore())),
            'extra_time'  => $this->when($hasExtraTimeScore, fn () => $this->transformPeriodScore($this->fixture->getExtraTimeScore())),
            'penalty'     => $this->when($hasPenaltyScore, fn () => $this->transformPeriodScore($this->fixture->getPenaltyPeriodScore())),
        ];
    }

    /**
     * @return array<string, int>
     */
    private function transformFixtureScore(): array
    {
        return [
            'home'  => $this->fixture->goalsHome()->toInt(),
            'away'  => $this->fixture->goalsAway()->toInt()
        ];
    }

    private function transformMatchWinner(): TeamResource
    {
        return new TeamResource(
            (new FixturesCollection([$this->fixture]))->teams()->findById($this->fixture->winnerId())
        );
    }

    /**
     * @return array<string, int>
     */
    private function transformPeriodScore(FixturePeriodGoals $score): array
    {
        return [
            'home'          => $score->goalsHome()->toInt(),
            'away'          => $score->goalsAway()->toInt(),
        ];
    }

    /**
     * @return array<mixed>
     */
    private function transFormFixtureStatus(): array
    {
        $value = fn (string $longInfo, string $shortInfo) => [
            'info'  => $longInfo,
            'short' => $shortInfo
        ];

        return match ($this->fixture->status()->code()) {
            FixtureStatus::TBD                          => $value('Time To Be Defined', 'TBD'),
            FixtureStatus::NOT_STARTED                  => $value('Not Started', 'NSD'),
            FixtureStatus::FIRST_HALF                   => $value('First Half', '1H'),
            FixtureStatus::HALF_TIME                    => $value('Half Time', 'HT'),
            FixtureStatus::SECOND_HALF                  => $value('Second Half', '2H'),
            FixtureStatus::EXTRA_TIME                   => $value('Extra Time', 'ET'),
            FixtureStatus::PENALTY_IN_PROGRESS          => $value('Penalty In Progress', 'P'),
            FixtureStatus::FULL_TIME                    => $value('Full Time', 'FT'),
            FixtureStatus::FINISHED_AFTER_EXTRA_TIME    => $value('Finished After Extra Time', 'AET'),
            FixtureStatus::FINISHED_AFTER_PENALTY       => $value('Finished After Penalty', 'AP'),
            FixtureStatus::EXTRA_TIME_BREAK             => $value('Extra Time Break', 'ETB'),
            FixtureStatus::SUSPENDED                    => $value('Suspended', 'SUSp'),
            FixtureStatus::INTERRUPTED                  => $value('Interrupted', 'INTD'),
            FixtureStatus::POSTPONED                    => $value('Postponed', 'POSP'),
            FixtureStatus::CANCELLED                    => $value('Cancelled', 'CACNC'),
            FixtureStatus::ABANDONED                    => $value('Abandoned', 'ABN'),
            FixtureStatus::TECHNICAL_LOSS               => $value('Technical Loss', 'THL'),
            FixtureStatus::WALK_OVER                    => $value('WalkOver', 'WO'),
            FixtureStatus::NO_COVERAGE                  => $value('NO Coverage', 'NCOV'),
        };
    }
}
