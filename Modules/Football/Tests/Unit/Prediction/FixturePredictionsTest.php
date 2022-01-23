<?php

declare(strict_types=1);

namespace Module\Football\Tests\Unit\Prediction;

use Module\Football\Prediction\FixturePredictionsResult;
use Tests\TestCase;

class FixturePredictionsTest extends TestCase
{
    public function test_will_throw_exception_when_values_are_not_equal_to_total(): void
    {
        $this->expectExceptionCode(988);

        new FixturePredictionsResult(1, 0, 0, 0);
    }
}
