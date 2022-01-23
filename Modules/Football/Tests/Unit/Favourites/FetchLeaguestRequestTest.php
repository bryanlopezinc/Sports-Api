<?php

declare(strict_types=1);

namespace Module\Football\Tests\Unit\Favourites;

use GuzzleHttp\Psr7\Response as Psr7Response;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Client\Response;
use Illuminate\Pagination\Paginator;
use Module\Football\Collections\LeaguesCollection;
use Module\Football\Contracts\Cache\LeaguesCacheInterface;
use Tests\TestCase;
use Module\Football\Favourites\Clients\FetchLeaguestRequest;
use Module\Football\Favourites\Models\Favourite;
use Module\Football\Tests\Stubs\ApiSports\V3\FetchLeagueResponse;
use Module\Football\ValueObjects\LeagueId;

class FetchLeaguestRequestTest extends TestCase
{
    public function test_will_not_return_leagues_in_cache(): void
    {
        $models = [
            $this->getModelInstance(['favourite_id' => 20, 'type' => Favourite::LEAGUE_TYPE]),
            $this->getModelInstance(['favourite_id' => 21, 'type' => Favourite::LEAGUE_TYPE])
        ];

        $cache = $this->getMockBuilder(LeaguesCacheInterface::class)->getMock();
        $cache->expects($this->exactly(2))->method('has')->willReturn(true, false);

        $this->swap(LeaguesCacheInterface::class, $cache);

        /** @var FetchLeaguestRequest */
        $request = app(FetchLeaguestRequest::class);

        $result = $request->buildRequestObjectsWith(new Paginator($models, 12));

        $this->assertCount(1, $result);
        $this->assertEquals($request->key(new LeagueId(21)), array_key_first($result));
    }

    public function test_will_return_correct_response(): void
    {
        $models = [
            $this->getModelInstance(['favourite_id' => 20, 'type' => Favourite::LEAGUE_TYPE]),
        ];

        $cache = $this->getMockBuilder(LeaguesCacheInterface::class)->getMock();
        $cache->expects($this->once())->method('has')->willReturn(false);
        $cache->expects($this->exactly(1))->method('findManyById')->willReturn(new LeaguesCollection([]));

        $this->swap(LeaguesCacheInterface::class, $cache);

        /** @var FetchLeaguestRequest */
        $request = app(FetchLeaguestRequest::class);

        $result = $request->buildRequestObjectsWith(new Paginator($models, 12));

        $result = $request->mapResponsesToDto([
            $request->key() => new Response(new Psr7Response(body: FetchLeagueResponse::json()))
        ]);

        $this->assertCount(1, $result);
    }

    private function getModelInstance(array $data): Model
    {
        return new class($data) extends Model
        {
            protected $guarded = [];
        };
    }
}
