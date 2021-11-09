<?php

declare(strict_types=1);

namespace Module\Football\ValueObjects;

use LogicException;

final class LeagueSeasonDuration
{
    public function __construct(private LeagueStartDate $start, private LeagueEndDate $end)
    {
        if ($start->toCarbon()->isAfter($end->toCarbon())) {
            throw new LogicException('league start date must be before end date');
        }
    }

    public function startDate(): LeagueStartDate
    {
        return $this->start;
    }

    public function endDate(): LeagueEndDate
    {
        return $this->end;
    }
}
