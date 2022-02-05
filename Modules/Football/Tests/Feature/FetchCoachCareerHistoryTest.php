<?php

declare(strict_types=1);

namespace Module\Football\Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Http;
use Illuminate\Testing\TestResponse;
use Module\Football\ValueObjects\CoachId;
use Module\Football\Routes\FetchCoachCareerHistoryRoute;
use Module\Football\Routes\RouteName;
use Module\Football\Tests\Stubs\ApiSports\V3\FetchCoachResponse;

class FetchCoachCareerHistoryTest extends TestCase
{
    private function getTestResponse(int $id): TestResponse
    {
        return $this->getJson(
            (string)new FetchCoachCareerHistoryRoute(new CoachId($id))
        );
    }

    public function test_success_response(): void
    {
        $this->withoutExceptionHandling();

        Http::fakeSequence()->push(FetchCoachResponse::json());

        $this->getTestResponse(12)->assertSuccessful();
    }

    public function test_will_return_validation_error_when_id_is_not_present(): void
    {
        Http::fake(fn () => Http::response(status: 404));

        $this->getJson(route(RouteName::COACH_CAREER_HISTORY))
            ->assertStatus(422)
            ->assertJsonValidationErrorFor('id');
    }

    public function test_will_return_404_status_code_when_coach_id_does_not_exists(): void
    {
        Http::fake(fn () => Http::response(status: 404));

        $this->getTestResponse(334)->assertNotFound();
    }
}
