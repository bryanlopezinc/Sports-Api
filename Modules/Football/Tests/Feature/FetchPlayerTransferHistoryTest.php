<?php

declare(strict_types=1);

namespace Module\Football\Tests\Feature;

use Tests\TestCase;
use Module\Football\Routes\RouteName;
use Illuminate\Support\Facades\Http;

class FetchPlayerTransferHistoryTest extends TestCase
{
    public function test_success_response(): void
    {
        $json = file_get_contents('Modules\Football\Tests\Stubs\ApiSports\V3\json\playerTransferHistory.json');

        Http::fake(fn () => Http::response($json));

        $this->withoutExceptionHandling()
            ->getJson(route(RouteName::PLAYER_TRANSFER_HISTORY, ['id' => $this->hashId(20)]))
            ->assertSuccessful();
    }

    public function test_will_return_404_status_code_when_player_id_does_not_exists(): void
    {
        Http::fake(fn () => Http::response(status: 404));

        $this->getJson(route(RouteName::PLAYER_TRANSFER_HISTORY, ['id' => $this->hashId(120)]))->assertNotFound();
    }
}
