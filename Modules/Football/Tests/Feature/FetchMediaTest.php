<?php

declare(strict_types=1);

namespace Module\Football\Tests\Feature;

use Tests\TestCase;
use Module\Football\Media\StreamGetContentsHttpClient;
use Module\Football\Media\FetchImageHttpClientInterface;

class FetchMediaTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->swap(FetchImageHttpClientInterface::class, $this->getImageHttpClient());
    }

    private function getImageHttpClient(): FetchImageHttpClientInterface
    {
        return new class implements FetchImageHttpClientInterface
        {
            public function response(string $url): string
            {
                return (new StreamGetContentsHttpClient)->response(base_path('Modules\Football\Tests\Stubs\Images\coach.png'));
            }
        };
    }

    public function test_fetch_assets_response(): void
    {
        $id = $this->hashId(20);

        $this->getJson(route('coach.photo', [$id]))->assertSuccessful()->assertHeader('Content-Type', 'image/png');
        $this->getJson(route('league.logo', [$id]))->assertSuccessful()->assertHeader('Content-Type', 'image/png');
        $this->getJson(route('player.photo', [$id]))->assertSuccessful()->assertHeader('Content-Type', 'image/png');
        $this->getJson(route('team.logo', [$id]))->assertSuccessful()->assertHeader('Content-Type', 'image/png');
    }
}
