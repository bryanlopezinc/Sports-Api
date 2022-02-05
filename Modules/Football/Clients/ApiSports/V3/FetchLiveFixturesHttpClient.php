<?php

declare(strict_types=1);

namespace Module\Football\Clients\ApiSports\V3;

use Illuminate\Support\Collection;
use Module\Football\Collections\FixturesCollection;
use Module\Football\Contracts\Repositories\FetchLiveFixturesRepositoryInterface;

final class FetchLiveFixturesHttpClient extends ApiSportsClient implements FetchLiveFixturesRepositoryInterface
{
    public function FetchLiveFixtures(): FixturesCollection
    {
        return  $this->get('fixtures', ['live' => 'all'])
            ->collect('response')
            ->map(new Response\FixtureResponseJsonMapper())
            ->pipe(fn (Collection $collection) => new FixturesCollection($collection->all()));
    }
}
