<?php

declare(strict_types=1);

namespace App\Utils;

final class RescueInitializationException
{
    public function __construct(
        private mixed $rescueWith = null,
        private bool $report = false
    ) {
    }

    public function rescue(callable $callback): mixed
    {
        $rescueCallback = function (\Throwable $e) {
            if (!str_ends_with($e->getMessage(), 'must not be accessed before initialization')) {
                throw $e;
            }

            return $this->rescueWith;
        };

        return rescue($callback, $rescueCallback, $this->report);
    }
}
