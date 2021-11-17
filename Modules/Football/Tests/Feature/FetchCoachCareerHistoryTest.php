<?php

declare(strict_types=1);

namespace Module\Football\Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Http;
use Illuminate\Testing\TestResponse;
use Module\Football\ValueObjects\CoachId;
use Module\Football\Routes\FetchCoachCareerHistoryRoute;
use Module\Football\Tests\Stubs\ApiSports\V3\FetchCoachResponse;

class FetchCoachCareerHistoryTest extends TestCase
{
    private function getTestRespone(int $id): TestResponse
    {
        return $this->getJson(
            (string)new FetchCoachCareerHistoryRoute(new CoachId($id))
        );
    }

    public function test_success_response(): void
    {
        $this->withoutExceptionHandling();

        Http::fakeSequence()
            ->push(FetchCoachResponse::json());

        $this->getTestRespone(12)
            ->assertSuccessful();
    }
}
