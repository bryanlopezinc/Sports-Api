<?php

declare(strict_types=1);

namespace Module\Football\Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Http;
use Module\Football\Routes\RouteName;

class FetchNewsHeadlineTest extends TestCase
{
    public function test_success_response(): void
    {
        $this->withoutExceptionHandling();

        Http::fakeSequence()
            ->push(file_get_contents(base_path('Modules\Football\Tests\Stubs\Goal.com\news.html')))
            ->push(file_get_contents(base_path('Modules\Football\Tests\Stubs\Goal.com\transfer-news.html')))
            ->push(file_get_contents(base_path('Modules\Football\Tests\Stubs\SkySports\latest.html')));

        $this->getJson(route(RouteName::NEWS))->assertSuccessful()->assertJsonCount(60, 'data');
    }
}
