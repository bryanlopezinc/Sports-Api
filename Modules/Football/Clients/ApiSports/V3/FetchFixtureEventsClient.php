<?php

declare(strict_types=1);

namespace Module\Football\Clients\ApiSports\V3;

use Module\Football\ValueObjects\FixtureId;
use Module\Football\Collections\FixtureEventsCollection;
use Module\Football\Contracts\Repositories\FetchFixtureEventsRepositoryInterface;

final class FetchFixtureEventsClient extends ApiSportsClient implements FetchFixtureEventsRepositoryInterface
{
    public function events(FixtureId $fixtureId): FixtureEventsCollection
    {
        return $this->get('fixtures/events', ['fixture' => $fixtureId->toInt()])
            ->collect('response')
            ->map(new Response\FixtureEventsResponseJsonMapper())
            ->pipeInto(FixtureEventsCollection::class);
    }
}
