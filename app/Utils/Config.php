<?php

declare(strict_types=1);

namespace App\Utils;

use App\Exceptions\MissingConfigKeyException;
use Illuminate\Support\Facades\Config as AppConfig;

final class Config
{
    /**
     * @throws MissingConfigKeyException
     */
    public static function get(mixed $key): mixed
    {
        return AppConfig::get($key, fn () => throw new MissingConfigKeyException($key));
    }
}
