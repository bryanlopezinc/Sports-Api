<?php

declare(strict_types=1);

namespace Module\Football\FixtureStatistic;

final class GenericFixtureStatistic extends AbstractStatistic
{
    private const VALID_STATISTIC_NAMES = [
        self::SHOTS_ON_GOAL,
        self::SHOTS_INSIDE_BOX,
        self::SHOTS_OUTSIDE_BOX,
        self::TOTAL_SHOTS,
        self::BLOCKED_SHOTS,
        self::FOULS,
        self::CORNER_KICKS,
        self::OFFSIDES,
        self::BALL_POSSESION,
        self::YELLOW_CARDS,
        self::RED_CARDS,
        self::GOALKEPPER_SAVES,
        self::PASSES,
        self::SHOTS_OFF_GOAL,
        self::ACCURATE_PASSES
    ];

    /** List of statistic types that cannot be assigned with this class */
    private const NOT_ASSIGNABLE = [
        self::BALL_POSSESION,
    ];

    public function __construct(private string $name, int $value)
    {
        parent::__construct($value);
    }

    public function name(): string
    {
        return $this->name;
    }

    protected function validate(): void
    {
        if (notInArray($this->name, self::VALID_STATISTIC_NAMES)) {
            throw new \InvalidArgumentException('Undefined fixture statistic name ' . $this->name);
        }

        if (inArray($this->name, self::NOT_ASSIGNABLE)) {
            throw new \InvalidArgumentException('Cannot make statisitic type ' . $this->name . ' with a generic class');
        }
    }
}
