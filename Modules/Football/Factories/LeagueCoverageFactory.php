<?php

declare(strict_types=1);

namespace Module\Football\Factories;

use Module\Football\DTO\LeagueCoverage;
use Module\Football\DTO\Builders\LeagueCoverageBuilder;

final class LeagueCoverageFactory extends Factory
{
    protected string $dtoClass = LeagueCoverage::class;

    public function definition()
    {
        return (new LeagueCoverageBuilder)
            ->supportsLineUp(true)
            ->supportsEvents(true)
            ->setSupportsStatistics(true)
            ->toArray();
    }

    public function toDto(): LeagueCoverage
    {
        return $this->mapToDto();
    }
}
