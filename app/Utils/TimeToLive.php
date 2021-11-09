<?php

declare(strict_types=1);

namespace App\Utils;

use Carbon\Carbon;

final class TimeToLive
{
    private function __construct(private Carbon $ttl)
    {
    }

    public static function seconds(int $seconds): static
    {
        return new static(now()->addSeconds($seconds));
    }

    public static function days(int $days): static
    {
        return new static(now()->addDays($days));
    }

    public static function hours(int $hours): static
    {
        return new static(now()->addHours($hours));
    }

    public static function minutes(int $minutes): static
    {
        return new static(now()->addMinutes($minutes));
    }

    public function ttl(): \DateTimeInterface|\DateInterval
    {
        return $this->ttl;
    }
}
