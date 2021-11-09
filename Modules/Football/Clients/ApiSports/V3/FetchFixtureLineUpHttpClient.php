<?php

declare(strict_types=1);

namespace Module\Football\Clients\ApiSports\V3;

use Illuminate\Support\Collection;
use Module\Football\FixtureLineUp;
use Module\Football\DTO\TeamLineUp;
use Module\Football\ValueObjects\FixtureId;
use Module\Football\Exceptions\Http\FixtureLineUpNotAvailableHttpException;
use Module\Football\Contracts\Repositories\FetchFixtureLineUpRepositoryInterface;

final class FetchFixtureLineUpHttpClient extends ApiSportsClient implements FetchFixtureLineUpRepositoryInterface
{
    public function fetchLineUp(FixtureId $id): FixtureLineUp
    {
        return $this->get('fixtures/lineups', ['fixture'  => $id->toInt()])
            ->collect('response')
            ->whenEmpty(fn () => throw new FixtureLineUpNotAvailableHttpException)
            ->map(fn (array $data): TeamLineUp => (new Response\TeamLineUpResponseJsonMapper($data))->toDataTransferObject())
            ->pipe(fn (Collection $collection) => new FixtureLineUp(...$collection->all()));
    }
}
