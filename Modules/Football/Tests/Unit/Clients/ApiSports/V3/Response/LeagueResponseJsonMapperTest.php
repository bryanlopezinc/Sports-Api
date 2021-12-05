<?php

declare(strict_types=1);

namespace Module\Football\Tests\Unit\Clients\ApiSports\V3\Response;

use Tests\TestCase;
use Illuminate\Support\Arr;
use Module\Football\ValueObjects\Season;
use Module\Football\Tests\Stubs\ApiSports\V3\FetchLeagueResponse;
use Module\Football\Clients\ApiSports\V3\Response\LeagueResponseJsonMapper;

class LeagueResponseJsonMapperTest extends TestCase
{
    public function test_will_set_current_season_to_requested_season(): void
    {
        $response = json_decode(FetchLeagueResponse::json(), true)['response'][0];

        $league = (new LeagueResponseJsonMapper($response))->tooDataTransferObject(new Season(2019));

        $this->assertEquals($league->getSeason()->getSeason()->toInt(), 2019);
    }

    public function test_will_default_to_current_season_if_no_season_is_requested(): void
    {
        $response = json_decode(FetchLeagueResponse::json(), true)['response'][0];

        $league = (new LeagueResponseJsonMapper($response))->tooDataTransferObject();

        $this->assertEquals($league->getSeason()->getSeason()->toInt(), 2021);
    }

    public function test_will_default_to_recently_concluded_season_if_no_season_is_current_and_no_season_is_requested(): void
    {
        $json = json_decode(FetchLeagueResponse::json(), true);

        Arr::forget($json, 'response.0.seasons.11'); //remove curent season

        $league = (new LeagueResponseJsonMapper($json['response'][0]))->tooDataTransferObject();

        $this->assertEquals($league->getSeason()->getSeason()->toInt(), 2020);
    }
}
