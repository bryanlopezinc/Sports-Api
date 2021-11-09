<?php

declare(strict_types=1);

namespace Module\Football\Contracts\Repositories;

use App\ValueObjects\Date;

interface FetchFixturesByDateRepositoryInterface
{
    /**
     * @return array<\Module\Football\LeagueFixturesGroup>
     */
    public function asGroup(Date $date): array;
}
