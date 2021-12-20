<?php

declare(strict_types=1);

namespace Module\Football\Http\Resources;

use Illuminate\Http\Request;
use Module\Football\FixtureEvents\VarEvent;
use Module\Football\ValueObjects\FixtureId;
use Module\Football\FixtureEvents\CardEvent;
use Module\Football\FixtureEvents\GoalEvent;
use Illuminate\Http\Resources\Json\JsonResource;
use Module\Football\FixtureEvents\SubstitutionEvent;
use Module\Football\FixtureEvents\MissedPenaltyEvent;
use Module\Football\FixtureEvents\TeamEventInterface;
use Module\Football\Collections\FixtureEventsCollection;

final class FixtureEventsResource extends JsonResource
{
    public function __construct(private FixtureEventsCollection $events)
    {
        parent::__construct($events);
    }

    /**
     * @param Request $request
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'type'          => 'football_events',
            'fixture_id'    => FixtureId::fromRequest($request)->asHashedId(),
            'events'        => $this->events->toLaravelCollection()->map(function (Object $event): array {
                return match ($event::class) {
                    CardEvent::class           => $this->transformCardEvent($event),
                    SubstitutionEvent::class   => $this->transformSubstitutionEvent($event),
                    GoalEvent::class           => $this->transformGoalEvent($event),
                    VarEvent::class            => $this->transformVarEvent($event),
                    MissedPenaltyEvent::class  => $this->transformMissedPenaltyEvent($event)
                };
            })->all()
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function transformCardEvent(CardEvent $cardEvent): array
    {
        return $this->mergeTeamEvent($cardEvent, [
            'event'       => 'card',
            'card_type'   => match (true) {
                $cardEvent->isRedCard()          => 'red',
                $cardEvent->isSecondYellowCard() => 'yellow-2',
                $cardEvent->isYellowCard()       => 'yellow'
            }
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    private function mergeTeamEvent(TeamEventInterface $event, array $with): array
    {
        return array_merge([
            'minute'    => $event->time()->minutes(),
            'team'      => new TeamResource($event->team())
        ], $with);
    }

    /**
     * @return array<string, mixed>
     */
    private function transformSubstitutionEvent(SubstitutionEvent $event): array
    {
        return $this->mergeTeamEvent($event, [
            'event' => 'substitution',
            'out'   => new PlayerResource($event->playerOut()),
            'in'    => new PlayerResource($event->playerIn()),
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    private function transformGoalEvent(GoalEvent $goal): array
    {
        return $this->mergeTeamEvent($goal, [
            'event'         => 'goal',
            'scorer'        => new PlayerResource($goal->goalScoredBy()),
            'has_assist'    => $goal->hasAssist(),
            'assist_by'     => $this->when($goal->hasAssist(), fn () => new PlayerResource($goal->goalAssistedBy())),
            'goal_type'     => match (true) {
                $goal->isNormalGoal()      => 'normal',
                $goal->isOwnGoal()         => 'own',
                $goal->isGoalByPenalty()   => 'penalty'
            },
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    private function transformMissedPenaltyEvent(MissedPenaltyEvent $missedPenalty): array
    {
        return $this->mergeTeamEvent($missedPenalty, [
            'event'      => 'missed_penalty',
            'missed_by'  => new PlayerResource($missedPenalty->missedBy()),
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    private function transformVarEvent(VarEvent $event): array
    {
        return $this->mergeTeamEvent($event, [
            'event'     => 'var',
            'decision'  => match (true) {
                $event->isCancelledGoal()  => 'Goal Cancelled',
                $event->penaltyAwarded()   => 'Penalty Awarded',
            }
        ]);
    }
}
