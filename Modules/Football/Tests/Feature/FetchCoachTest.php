<?php

declare(strict_types=1);

namespace Module\Football\Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Http;
use Illuminate\Testing\TestResponse;
use Module\Football\ValueObjects\CoachId;
use Module\Football\Routes\FetchCoachRoute;
use Module\Football\Tests\Stubs\ApiSports\V3\FetchCoachResponse;

class FetchCoachTest extends TestCase
{
    private function getTestResponse(int $id): TestResponse
    {
        return $this->getJson(
            (string)new FetchCoachRoute(new CoachId($id))
        );
    }

    public function test_success_response(): void
    {
        $this->withoutExceptionHandling();

        Http::fakeSequence()->push(FetchCoachResponse::json());

        $this->getTestResponse(12)->assertSuccessful();
    }

    public function test_will_return_404_status_code_when_coach_id_does_not_exists(): void
    {
        Http::fake(fn () => Http::response(status: 404));

        $this->getTestResponse(334)->assertNotFound();
    }
}
