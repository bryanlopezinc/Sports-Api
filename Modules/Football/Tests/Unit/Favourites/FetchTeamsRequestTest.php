<?php

declare(strict_types=1);

namespace Module\Football\Tests\Unit\Favourites;

use GuzzleHttp\Psr7\Response as Psr7Response;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Client\Pool;
use Illuminate\Http\Client\Response;
use Illuminate\Pagination\Paginator;
use Module\Football\Collections\TeamsCollection;
use Module\Football\Contracts\Cache\TeamsCacheInterface;
use Tests\TestCase;
use Module\Football\Favourites\Clients\FetchTeamsRequest;
use Module\Football\Favourites\Models\Favourite;
use Module\Football\Tests\Stubs\ApiSports\V3\FetchTeamResponse;
use Module\Football\ValueObjects\TeamId;

class FetchTeamsRequestTest extends TestCase
{
    public function test_will_not_return_team_in_cache(): void
    {
        $models = [
            $this->getModelInstance(['favourite_id' => 20, 'type' => Favourite::TEAM_TYPE]),
            $this->getModelInstance(['favourite_id' => 21, 'type' => Favourite::TEAM_TYPE])
        ];

        $cache = $this->getMockBuilder(TeamsCacheInterface::class)->getMock();
        $cache->expects($this->exactly(2))->method('has')->willReturn(true, false);

        $this->swap(TeamsCacheInterface::class, $cache);

        /** @var FetchTeamsRequest */
        $request = app(FetchTeamsRequest::class);

        $result = $request->configure(new Pool(), new Paginator($models, 12));

        $this->assertCount(1, $result);
    }

    public function test_will_return_correct_response(): void
    {
        $models = [
            $this->getModelInstance(['favourite_id' => 20, 'type' => Favourite::TEAM_TYPE]),
        ];

        $cache = $this->getMockBuilder(TeamsCacheInterface::class)->getMock();
        $cache->expects($this->once())->method('has')->willReturn(false);
        $cache->expects($this->exactly(1))->method('getMany')->willReturn(new TeamsCollection([]));

        $this->swap(TeamsCacheInterface::class, $cache);

        /** @var FetchTeamsRequest */
        $request = app(FetchTeamsRequest::class);

        $result = $request->configure(new Pool(), new Paginator($models, 12));

        $result = $request->toDataTransferObject([
            $request->key() => new Response(new Psr7Response(body: FetchTeamResponse::json()))
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
