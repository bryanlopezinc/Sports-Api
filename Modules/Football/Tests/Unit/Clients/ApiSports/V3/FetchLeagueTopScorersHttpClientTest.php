<?php

declare(strict_types=1);

namespace Module\Football\Tests\Unit\Clients\ApiSports\V3;

use Tests\TestCase;
use Illuminate\Support\Facades\Http;
use Module\Football\ValueObjects\Season;
use Module\Football\ValueObjects\LeagueId;
use Module\Football\Clients\ApiSports\V3\FetchLeagueTopScorersHttpClient;
use Module\Football\Exceptions\Http\LeagueTopScorersNotAvailableHttpException;

class FetchLeagueTopScorersHttpClientTest extends TestCase
{
    /**
     * @test
     */
    public function client_will_throw_http_exception_when_top_scorers_response_is_empty(): void
    {
        $this->expectException(LeagueTopScorersNotAvailableHttpException::class);

        Http::fake(fn () => Http::response(status:204));

        (new FetchLeagueTopScorersHttpClient())->topScorerers(new LeagueId(23), new Season(2021));
    }
}
