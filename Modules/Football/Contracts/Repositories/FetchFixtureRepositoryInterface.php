<?php

declare(strict_types=1);

namespace Module\Football\Contracts\Repositories;

use Module\Football\DTO\Fixture;
use Module\Football\ValueObjects\FixtureId;

interface FetchFixtureRepositoryInterface
{
    /**
     * Only id, name, logo are returned for each team in the fixture.
     * The full fixture data and full LEAGUE data of the fixture league is returned in this response.
     *
     * @throws \App\Exceptions\Http\ResourceNotFoundHttpException;
     */
    public function FindFixtureById(FixtureId $id): Fixture;
}
