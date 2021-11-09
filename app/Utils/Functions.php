<?php

declare(strict_types=1);

use Carbon\Carbon;

/**
 * wrapper function for php 'in_array' with strict check
 * @param array<mixed> $array
 */
function inArray(mixed $needle, array $array): bool
{
    return in_array($needle, $array, true);
}

/**
 * @param array<mixed> $array
 */
function notInArray(mixed $needle, array $array): bool
{
    return !in_array($needle, $array, true);
}

function minutesUntilTommorow(): int
{
    return now()->diffInMinutes(Carbon::tomorrow());
}
