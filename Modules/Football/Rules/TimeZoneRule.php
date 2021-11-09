<?php

declare(strict_types=1);

namespace Module\Football\Rules;

use Illuminate\Contracts\Validation\Rule;
use Module\Football\ValueObjects\TimeZone;
use Module\Football\Exceptions\InvalidTimeZoneException;

final class TimeZoneRule implements Rule
{
    /**
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        try {
            TimeZone::fromString((string) $value);

            return true;
        } catch (InvalidTimeZoneException) {
            return false;
        }
    }

    /**
     * @return string|array<string>
     */
    public function message()
    {
        return 'invalid timezone';
    }
}
