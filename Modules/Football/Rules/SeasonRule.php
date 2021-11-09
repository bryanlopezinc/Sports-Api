<?php

declare(strict_types=1);

namespace Module\Football\Rules;

use Module\Football\ValueObjects\Season;
use Illuminate\Contracts\Validation\Rule;
use Module\Football\Exceptions\InvalidSeasonException;

final class SeasonRule implements Rule
{
    /**
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        try {
            new Season((int)$value);

            return true;
        } catch (InvalidSeasonException) {
            return false;
        }
    }

    /**
     * @return string|array<string>
     */
    public function message()
    {
        return 'The season should be greater than ' . Season::minSeason();
    }
}
