<?php

declare(strict_types=1);

namespace Module\Football\DTO\Builders;

use Module\Football\DTO\LeagueCoverage;

final class LeagueCoverageBuilder extends Builder
{
    public function setSupportsStatistics(bool $supportsStatistics): self
    {
        return $this->set('statistics', $supportsStatistics);
    }

    public function supportsEvents(bool $events): self
    {
        return $this->set('coversEvents', $events);
    }

    public function supportsTopScorers(bool $supportsTopScorers): self
    {
        return $this->set('coverTopScorers', $supportsTopScorers);
    }

    public function supportsTopAssists(bool $supportsTopAssists): self
    {
        return $this->set('coversTopAssists', $supportsTopAssists);
    }

    public function supportsLineUp(bool $lineUp): self
    {
        $this->set('coverslineUp', $lineUp);

        return $this;
    }

    public function build(): LeagueCoverage
    {
        return new LeagueCoverage($this->attributes);
    }
}
