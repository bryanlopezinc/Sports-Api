<?php

declare(strict_types=1);

namespace Module\Football\Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Http;
use Module\Football\ValueObjects\PlayerId;
use Module\Football\Routes\FetchPlayerRoute;
use Module\Football\Routes\RouteName;
use Module\Football\Tests\Stubs\ApiSports\V3\FetchPlayerResponse;

class FetchPlayerTest extends TestCase
{
    public function test_success_response(): void
    {
        Http::fakeSequence()->push(FetchPlayerResponse::json());

        $this->withoutExceptionHandling()->getJson((string) new FetchPlayerRoute(new PlayerId(40)))->assertSuccessful();
    }

    public function test_will_throw_validation_error_when_player_id_is_missing(): void
    {
        $this->getJson(route(RouteName::FIND_PLAYER))->assertJsonValidationErrors(['id']);
    }
}
