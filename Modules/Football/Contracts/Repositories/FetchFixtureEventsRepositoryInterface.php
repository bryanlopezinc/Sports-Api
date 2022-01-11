<?php

declare(strict_types=1);

namespace Module\Football\Contracts\Repositories;

use Module\Football\ValueObjects\FixtureId;
use Module\Football\Collections\FixtureEventsCollection;

interface FetchFixtureEventsRepositoryInterface
{
    /**
     * @throws \App\Exceptions\Http\ResourceNotFoundHttpException
     */
    public function events(FixtureId $fixtureId): FixtureEventsCollection;
}
