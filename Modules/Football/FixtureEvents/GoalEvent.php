<?php

declare(strict_types=1);

namespace Module\Football\FixtureEvents;

use Module\Football\DTO\Player;

final class GoalEvent implements TeamEventInterface
{
    use HasTeamEvent;

    public const OWN_GOAL        = 'Own Goal';
    public const PENALTY         = 'Penalty';
    public const NORMAL_GOAL     = 'Normal Goal';

    public function __construct(
        private Player $scoredBy,
        private string $type,
        private ?Player $assistedBy,
        private TeamEvent $teamEvent,
    ) {
        $this->validate();
    }

    private function validate(): void
    {
        if (notInArray($this->type, [self::OWN_GOAL, self::PENALTY, self::NORMAL_GOAL])) {
            throw new \InvalidArgumentException('Invalid Goal type ' . $this->type);
        }

        //Only normal goals can have an assist
        if (!$this->isNormalGoal() && $this->hasAssist()) {
            throw new \LogicException(
                sprintf('%s goal type cannot have an assist', $this->isGoalByPenalty() ? 'penalty' : 'own goal'),
                422
            );
        }

        if ($this->isNormalGoal() && $this->hasAssist()) {
            $this->ensurePlayerDidNotAssistSelf();
        }
    }

    private function ensurePlayerDidNotAssistSelf(): void
    {
        if ($this->goalAssistedBy()->getId()->equals($this->goalScoredBy()->getId())) {
            throw new \LogicException('Player cannot assist self', 423);
        }
    }

    public function goalScoredBy(): Player
    {
        return $this->scoredBy;
    }

    public function isNormalGoal(): bool
    {
        return $this->type === self::NORMAL_GOAL;
    }

    public function isOwnGoal(): bool
    {
        return $this->type === self::OWN_GOAL;
    }

    public function isGoalByPenalty(): bool
    {
        return $this->type === self::PENALTY;
    }

    public function type(): string
    {
        return $this->type;
    }

    public function hasAssist(): bool
    {
        return !is_null($this->assistedBy);
    }

    public function goalAssistedBy(): Player
    {
        return $this->assistedBy;
    }
}
