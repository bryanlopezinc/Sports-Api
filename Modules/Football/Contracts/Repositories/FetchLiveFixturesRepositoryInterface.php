<?php

declare(strict_types=1);

namespace Module\Football\Contracts\Repositories;

use Module\Football\Collections\FixturesCollection;

interface FetchLiveFixturesRepositoryInterface
{
    /**
     * only the league id, name, country, logo and season(year) are returned for the fixture league.
     * only name and city (if available) attributes are returned for the fixture venue.
     * Only id, name, logo are returned for each team in the fixture.
     * The full fixture data are returned in the response.
     */
    public function FetchLiveFixtures(): FixturesCollection;
}
