<?php

declare(strict_types=1);

namespace Module\Football\Rules;

use Illuminate\Contracts\Validation\Rule;
use Module\Football\Exceptions\InvalidPartialResourceFieldsException;

final class PartialFixtureFieldsRule implements Rule
{
    private const ALLOWED = [
        'id',
        'referee',
        'date',
        'venue',
        'minutes_elapsed',
        'status',
        'league',
        'winner',
        'teams',
        'score',
        'links',
        'period_goals',
        'period_goals.first_half',
        'period_goals.second_half',
        'period_goals.extra_time',
        'period_goals.penalty'
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
