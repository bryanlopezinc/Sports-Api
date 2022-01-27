<?php

namespace Tests;

use App\HashId\HashIdInterface;
use Illuminate\Database\Events\MigrationsEnded;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Event;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();

        Event::listen(MigrationsEnded::class, function () {
            $this->artisan('db:seed');
        });
    }

    protected function hashId(int $id): string
    {
        /** @var HashIdInterface */
        $hasher = app(HashIdInterface::class);

        return $hasher->hash($id);
    }
}
