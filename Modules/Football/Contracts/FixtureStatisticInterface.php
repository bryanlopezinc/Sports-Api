<?php

declare(strict_types=1);

namespace Module\Football\Contracts;

interface FixtureStatisticInterface
{
    public const SHOTS_ON_GOAL      = 'SHOTS ON GOAL';
    public const SHOTS_INSIDE_BOX   = 'SHOTS INSIDE BOX';
    public const SHOTS_OUTSIDE_BOX  = 'SHOTS OUTSIDE BOX';
    public const TOTAL_SHOTS        = 'TOTAL SHOTS';
    public const BLOCKED_SHOTS      = 'BLOCKED SHOTS';
    public const FOULS              = 'FOULS';
    public const CORNER_KICKS       = 'CORNER KICKS';
    public const OFFSIDES           = 'OFFSIDES';
    public const BALL_POSSESION     = 'BALL POSSESSION';
    public const YELLOW_CARDS       = 'YELLOW CARDS';
    public const RED_CARDS          = 'RED CARDS';
    public const GOALKEPPER_SAVES   = 'KEPPER SAVES';
    public const PASSES             = 'PASSES';
    public const SHOTS_OFF_GOAL     = 'SHOTS OFF GOAL';
    public const ACCURATE_PASSES    = 'ACCURATE PASSES';

    /*** The name of the fixture statistic can be any of the defined constants */
    public function name(): string;

    public function value(): int;
}
