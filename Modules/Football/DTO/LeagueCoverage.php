<?php

declare(strict_types=1);

namespace Module\Football\DTO;

use App\DTO\DataTransferObject;

final class LeagueCoverage extends DataTransferObject
{
    protected bool $statistics;
    protected bool $coverslineUp;
    protected bool $coversEvents;
    protected bool $coverTopScorers;

    public function coversStatistics(): bool
    {
        return $this->statistics;
    }

    public function coversTopScorers(): bool
    {
        return $this->coverTopScorers;
    }

    public function coverslineUp(): bool
    {
        return $this->coverslineUp;
    }

    public function coversEvents(): bool
    {
        return $this->coversEvents;
    }
}
