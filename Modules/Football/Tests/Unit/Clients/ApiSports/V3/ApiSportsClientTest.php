<?php

declare(strict_types=1);

namespace Module\Football\Tests\Unit\Clients\ApiSports\V3;

use App\Exceptions\Http\ResourceNotFoundHttpException;
use Tests\TestCase;
use Illuminate\Support\Facades\Http;
use Module\Football\Clients\ApiSports\V3\ApiSportsClient;

/**
 * @group apc
 */
class ApiSportsClientTest extends TestCase
{
    public function test_will_cache_404_error_response(): void
    {
        $this->expectException(ResourceNotFoundHttpException::class);

        $path = 'kemosabe';

        Http::fake(fn () => Http::response(status: 404));

        $this->makeRequestTo($path);

        Http::assertSentCount(1);
        Http::clearResolvedInstance(Http::getFacadeRoot());

        $this->makeRequestTo($path);

        Http::assertNothingSent();
    }

    private function makeRequestTo(string $uri): void
    {
        $client = new class extends ApiSportsClient
        {
            public function fetchFoo(string $uri)
            {
                return $this->get($uri)->json();
            }
        };

        $client->fetchFoo($uri);
    }
}
