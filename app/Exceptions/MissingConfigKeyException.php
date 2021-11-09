<?php

declare(strict_types=1);

namespace App\Exceptions;

final class MissingConfigKeyException extends \Exception
{
    public function __construct(string $key)
    {
        parent::__construct(
            'missing config key ' . $key
        );
    }
}
