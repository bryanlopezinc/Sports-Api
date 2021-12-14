<?php

declare(strict_types=1);

namespace Module\Football\Rules;

use Illuminate\Contracts\Validation\Rule;
use Module\Football\Exceptions\InvalidPartialResourceFieldsException;

final class PartialLeagueFieldsRule implements Rule
{
    private const ALLOWED = [
        'logo_url',
        'name',
        'country',
        'season',
        'season.season',
        'season.start',
        'season.end',
        'season.is_current_season',
        'coverage',
        'coverage.line_up',
        'coverage.events',
        'coverage.stats',
        'coverage.top_scorers',
        'coverage.top_assists',
        'links',
        'id'
    ];

    private string $message;

    /**
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        try {
            $this->validate(explode(',', $value));

            return true;
        } catch (InvalidPartialResourceFieldsException $e) {
            $this->message = $e->getMessage();

            return false;
        }
    }

    /**
     * @return string
     */
    public function message()
    {
        return $this->message;
    }

    private function validate(array $requestedFields): void
    {
        // Only id cannot be requested
        if (count($requestedFields) === 1 && inArray('id', $requestedFields)) {
            throw new InvalidPartialResourceFieldsException('Only id field cannot be requested');
        }

        foreach ($requestedFields as $field) {
            if (!inArray($field, self::ALLOWED)) {
                throw new InvalidPartialResourceFieldsException('The given partial resource fields are Invalid');
            }
        }
    }
}
