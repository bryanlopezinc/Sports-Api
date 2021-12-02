<?php

declare(strict_types=1);

namespace Module\Football\Clients\ApiSports\V3\Response;

use Module\Football\DTO\Player;
use Illuminate\Support\Collection;
use Module\Football\FixtureEvents\VarEvent;
use Module\Football\FixtureEvents as Events;
use Module\Football\FixtureEvents\CardEvent;
use Module\Football\FixtureEvents\GoalEvent;
use Module\Football\DTO\Builders\TeamBuilder;
use Module\Football\ValueObjects\TimeElapsed;
use Module\Football\DTO\Builders\PlayerBuilder;
use Module\Football\FixtureEvents\MissedPenaltyEvent;
use Module\Football\Collections\FixtureEventsCollection;

final class FixtureEventsResponseJsonMapper
{
    /**
     * @param array<string, mixed> $data
     */
    public function __construct(
        private array $data,
        private ?TeamBuilder $teamBuilder = null,
        private ?PlayerBuilder $playerBuilder = null
    ) {
        $this->response = new Response($data);
    }

    public function toCollection(): FixtureEventsCollection
    {
        return collect($this->data)
            ->map(fn (array $event) => match (strtolower($event['type'])) {
                'goal'  => $this->mapGoalEventType($event),
                'card'  => $this->mapCardEventType($event),
                'subst' => $this->mapSubstitutionType($event),
                'var'   => $this->mapVarEventType($event),
            })
            ->pipe(fn (Collection $collection) => new FixtureEventsCollection($collection->all()));
    }

    /**
     * @param array<string, mixed> $data
     */
    private function mapEventInfo(array $data): Events\TeamEvent
    {
        return new Events\TeamEvent(
            (new TeamJsonMapper($data['team'], $this->teamBuilder))->toDataTransferObject(),
            new TimeElapsed($data['time']['elapsed'])
        );
    }

    /**
     * @param array<string, mixed> $data
     */
    private function mapSubstitutionType(array $data): Events\SubstitutionEvent
    {
        return new Events\SubstitutionEvent(
            $this->mapPlayer($data['assist']),
            $this->mapPlayer($data['player']),
            $this->mapEventInfo($data)
        );
    }

    /**
     * @param array<string, mixed> $data
     */
    private function mapCardEventType(array $data): Events\CardEvent
    {
        $cardType = match (strtolower($data['detail'])) {
            'red card'              => CardEvent::RED_CARD,
            'second yellow card'    => CardEvent::SECOND_YELLOW,
            'yellow card'           => CardEvent::YELLOW_CARD,
        };

        return new Events\CardEvent($cardType, $this->mapPlayer($data['player']), $this->mapEventInfo($data));
    }

    /**
     * @param array<string, mixed> $data
     */
    private function mapVarEventType(array $data): Events\VarEvent
    {
        $type = match (strtolower($data['detail'])) {
            'penalty confirmed' => VarEvent::PENALTY_AWARDED,
            'goal cancelled'    => VarEvent::GOAL_CANCELLED,
        };

        return new Events\VarEvent($type, $this->mapEventInfo($data));
    }

    /**
     * @param array<string, mixed> $data
     */
    private function mapGoalEventType(array $data): Events\GoalEvent|MissedPenaltyEvent
    {
        if ($data['detail'] === 'Missed Penalty') {
            return $this->mapMissedPenaltyEventType($data);
        }

        $goalType = match (strtolower($data['detail'])) {
            'penalty'           => GoalEvent::PENALTY,
            'own goal'          => GoalEvent::OWN_GOAL,
            'normal goal'       => GoalEvent::NORMAL_GOAL,
        };

        return new Events\GoalEvent(
            $this->mapPlayer($data['player']),
            $goalType,
            filled($data['assist']['id']) ? $this->mapPlayer($data['assist']) : null,
            $this->mapEventInfo($data)
        );
    }

    /**
     * @param array<string, mixed> $data
     */
    private function mapMissedPenaltyEventType(array $data): MissedPenaltyEvent
    {
        return new MissedPenaltyEvent(
            $this->mapPlayer($data['player']),
            $this->mapEventInfo($data)
        );
    }

    /**
     * @param array<string, mixed> $data
     */
    private function mapPlayer(array $data): Player
    {
        return (new PlayerResponseJsonMapper($data, playerBuilder: $this->playerBuilder))->toDataTransferObject();
    }
}
