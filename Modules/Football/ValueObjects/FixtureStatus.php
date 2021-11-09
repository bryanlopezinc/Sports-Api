<?php

declare(strict_types=1);

namespace Module\Football\ValueObjects;

use ReflectionClass;

final class FixtureStatus
{
    public const TBD                         = 100;
    public const NOT_STARTED                 = 000;
    public const FIRST_HALF                  = 1;
    public const HALF_TIME                   = 45;
    public const SECOND_HALF                 = 46;
    public const FULL_TIME                   = 90;
    public const CONFIRMING_EXTRA_TIME       = 91;
    public const EXTRA_TIME                  = 92;
    public const EXTRA_TIME_BREAK            = 105;
    public const FINISHED_AFTER_EXTRA_TIME   = 120;
    public const PENALTY_IN_PROGRESS         = 121;
    public const FINISHED_AFTER_PENALTY      = 131;
    public const SUSPENDED                   = 300;
    public const POSTPONED                   = 301;
    public const CANCELLED                   = 302;
    public const INTERRUPTED                 = 400;
    public const ABANDONED                   = 401;
    public const TECHNICAL_LOSS              = 402;
    public const NO_COVERAGE                 = 403;
    public const WALK_OVER                   = 600;

    public function __construct(private int $code)
    {
        $this->validate();
    }

    private function validate(): void
    {
        if (notInArray($this->code, (new ReflectionClass($this))->getConstants())) {
            throw new \InvalidArgumentException('Invalid fixture status ' . $this->code);
        }
    }

    public static function create(int $code): static
    {
        return new static($code);
    }

    public function isInProgress(): bool
    {
        return !$this->isFinished();
    }

    public function isStartedButNoCoverage(): bool
    {
        return $this->code === self::NO_COVERAGE;
    }

    public function istechnicalLoss(): bool
    {
        return $this->code === self::TECHNICAL_LOSS;
    }

    public function isPostponed(): bool
    {
        return $this->code === self::POSTPONED;
    }

    public function timeIsYetToBeDefined(): bool
    {
        return $this->code === self::TBD;
    }

    public function isSuspended(): bool
    {
        return $this->code === self::SUSPENDED;
    }

    public function isHalfTime(): bool
    {
        return $this->code === self::HALF_TIME;
    }

    public function isExtraTime(): bool
    {
        return $this->code === self::EXTRA_TIME;
    }

    public function isPenaltyPeriod(): bool
    {
        return $this->code === self::PENALTY_IN_PROGRESS;
    }

    public function isNotStarted(): bool
    {
        return $this->code === self::NOT_STARTED;
    }

    /**
     * can be that fixture was postponed or cancelled.
     */
    public function didNotStartForVariousReasons(): bool
    {
        return $this->isPostponed() || $this->isCancelled();
    }

    public function isCancelled(): bool
    {
        return $this->code === self::CANCELLED;
    }

    public function isAbandoned(): bool
    {
        return $this->code === self::ABANDONED;
    }

    public function isFinished(): bool
    {
        if (
            $this->code === self::CONFIRMING_EXTRA_TIME ||
            $this->isStartedButNoCoverage()
        ) {
            return false;
        }

        return inArray($this->code, [
            self::FULL_TIME,
            self::FINISHED_AFTER_EXTRA_TIME,
            self::FINISHED_AFTER_PENALTY,
            self::WALK_OVER,
        ]);
    }

    public function isFullTime(): bool
    {
        return $this->code === self::FULL_TIME;
    }

    public function isFirstPeriod(): bool
    {
        return $this->code === self::FIRST_HALF;
    }

    public function isSecondPeriod(): bool
    {
        return $this->code === self::SECOND_HALF;
    }

    public function penaltyInProgress(): bool
    {
        return $this->code === self::PENALTY_IN_PROGRESS;
    }

    public function code(): int
    {
        return $this->code;
    }
}
