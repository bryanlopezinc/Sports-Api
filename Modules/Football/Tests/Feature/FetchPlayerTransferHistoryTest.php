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
}
