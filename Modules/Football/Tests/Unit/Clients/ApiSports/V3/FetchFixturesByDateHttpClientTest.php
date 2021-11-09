<?php

declare(strict_types=1);

namespace Module\Football\Tests\Feature;

use App\ValueObjects\Date;
use Tests\TestCase;
use Illuminate\Support\Facades\Http;
use Module\Football\Clients\ApiSports\V3\FetchFixturesByDateHttpClient;
use Module\Football\Tests\Stubs\ApiSports\V3\FetchFixtureByDateResponse;

class FetchFixturesByDateHttpClientTest extends TestCase
{
    /**
     * @test
     */
    public function client_will_group_league_fixtures_correctly(): void
    {
        Http::fake(fn () => Http::response(FetchFixtureByDateResponse::json()));

        $reponse = (new FetchFixturesByDateHttpClient())->asGroup(new Date(now()->toDateString()));

        $this->assertEquals($reponse['265']->fixturesCount(), 3);
        $this->assertEquals($reponse['489']->fixturesCount(), 1);
        $this->assertEquals($reponse['843']->fixturesCount(), 3);
        $this->assertEquals($reponse['263']->fixturesCount(), 3);
        $this->assertEquals($reponse['128']->fixturesCount(), 4);
        $this->assertEquals($reponse['523']->fixturesCount(), 2);
        $this->assertEquals($reponse['138']->fixturesCount(), 2);
        $this->assertEquals($reponse['253']->fixturesCount(), 7);
        $this->assertEquals($reponse['255']->fixturesCount(), 5);
        $this->assertEquals($reponse['880']->fixturesCount(), 13);
        $this->assertEquals($reponse['848']->fixturesCount(), 16);
        $this->assertEquals($reponse['3']->fixturesCount(), 14);
        $this->assertEquals($reponse['71']->fixturesCount(), 1);
        $this->assertEquals($reponse['262']->fixturesCount(), 4);
        $this->assertEquals($reponse['343']->fixturesCount(), 1);
    }
}
