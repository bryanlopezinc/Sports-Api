<?php

declare(strict_types=1);

namespace Tests\Unit\HashId;

use Tests\TestCase;
use App\HashId\HashId;
use App\HashId\CannotDecodeHashIdException;

class HashIdTest extends TestCase
{
    public function test_will_throw_exception_if_hash_id_is_invalid(): void
    {
        $this->expectException(CannotDecodeHashIdException::class);

        $hashId = new HashId('secret-key', 8);

        $hashId->decode($hashId->hash(123) . 'foo');
    }
}
