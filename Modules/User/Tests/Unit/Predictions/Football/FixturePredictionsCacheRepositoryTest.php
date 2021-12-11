<?php

declare(strict_types=1);

namespace Module\User\Tests\Unit\Predictions\Football;

use Tests\TestCase;
use App\Utils\TimeToLive;
use Illuminate\Support\Facades\Cache;
use Module\Football\ValueObjects\FixtureId;
use Module\User\Predictions\Football\FixturePredictionsTotals;
use Module\User\Predictions\Football\FixturePredictionsCacheRepository;

class FixturePredictionsCacheRepositoryTest extends TestCase
{
    public function test_cache_predictions(): void
    {
        $fixtureId = new FixtureId(22);

        $cache = Cache::store();
        $cache->clear();

        $repository = new FixturePredictionsCacheRepository($cache);

        $this->assertFalse($repository->has($fixtureId));
        $this->assertTrue($repository->put($fixtureId, new FixturePredictionsTotals(2, 2, 2, 6), TimeToLive::minutes(1)));
        $this->assertTrue($repository->has($fixtureId));
        $this->assertEquals($repository->get($fixtureId), new FixturePredictionsTotals(2, 2, 2, 6));
        $this->assertTrue($repository->forgetPredictionFor($fixtureId));
        $this->assertFalse($repository->has($fixtureId));
    }
}
