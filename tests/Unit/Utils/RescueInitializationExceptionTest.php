<?php

declare(strict_types=1);

namespace Tests\Unit\Utils;

use App\Utils\RescueInitializationException;
use Tests\TestCase;

class RescueInitializationExceptionTest extends TestCase
{
    public function test_rescues_initialization_exception(): void
    {
        $rescuer = new RescueInitializationException('foo');

        $class = new class
        {
            public int $value;
        };

        $value = $rescuer->rescue(fn () => $class->value);

        $this->assertEquals($value, 'foo');
    }

    public function test_rescues_only_initialization_exception(): void
    {
        $this->expectExceptionMessage('class@anonymous::get(): Return value must be of type string, int returned');

        $rescuer = new RescueInitializationException;

        $class = new class
        {
            private int $value = 2;

            public function get(): string
            {
                return $this->value; // @phpstan-ignore-line
            }
        };

        $rescuer->rescue(fn () => $class->get());
    }
}
