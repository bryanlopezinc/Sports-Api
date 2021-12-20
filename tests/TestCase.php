<?php

namespace Tests;

use App\HashId\HashIdInterface;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function hashId(int $id): string
    {
        /** @var HashIdInterface */
        $hasher = app(HashIdInterface::class);

        return $hasher->hash($id);
    }
}
