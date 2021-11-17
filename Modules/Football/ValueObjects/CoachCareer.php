<?php

declare(strict_types=1);

namespace Module\Football\ValueObjects;

use App\ValueObjects\Date;
use Module\Football\DTO\Team;

/**
 * Represents a Coach's time in a team.
 */
final class CoachCareer
{
    /**
     * The team is the coachs current team If the end date is set to null
     */
    public function __construct(private Team|Name $team, private Date $from, private ?Date $to)
    {
        $this->validate();
    }

    private function validate(): void
    {
        if ($this->isTeamCurrentManger()) {
            return;
        }

        if ($this->from->toCarbon()->isAfter($this->to->toCarbon())) {
            throw new \LogicException('Carrer start date must be before career end date');
        }
    }

    public function teamManaged(): Team|Name
    {
        return $this->team;
    }

    public function onlyTeamManagedNameIsAvailable(): bool
    {
        return $this->team instanceof Name;
    }

    public function startedManagementOn(): Date
    {
        return $this->from;
    }

    public function isTeamCurrentManger(): bool
    {
        return $this->to === null;
    }

    public function leftTeamOn(): Date
    {
        return $this->to;
    }
}
