<?php

declare(strict_types=1);

namespace Tests\Unit\ValueObjects;

use Tests\TestCase;
use App\ValueObjects\ResourceId;
use App\Exceptions\InvalidResourceIdException;

class ResourceIdTest extends TestCase
{
    public function test_throws_exception_when_int_id_is_less_than_one(): void
    {
        $this->expectException(InvalidResourceIdException::class);

        new ResourceId(0);
    }
}
