<?php

declare(strict_types=1);

namespace Module\Football\Rules;

use Illuminate\Contracts\Validation\Rule;
use Module\Football\Exceptions\InvalidPartialResourceFieldsException;

final class PartialFixtureFieldsRule implements Rule
{
    use RetrievePartialResourceField;

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
    public int $code;

    /**
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        try {
            $this->validate(explode(',', $this->getValue($value)));

            return true;
        } catch (InvalidPartialResourceFieldsException $e) {
            $this->message = $e->getMessage();
            $this->code = $e->getCode();

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
            throw new InvalidPartialResourceFieldsException('Only id field cannot be requested', 100);
        }

        foreach ($requestedFields as $field) {
            if (notInArray($field, self::ALLOWED)) {
                throw new InvalidPartialResourceFieldsException("The given partial resource field $field is invalid", 101);
            }
        }

        if (inArray('period_goals', $requestedFields)) {
            foreach ($requestedFields as $field) {
                if (inArray($field, [
                    'period_goals.first_half',
                    'period_goals.second_half',
                    'period_goals.extra_time',
                    'period_goals.penalty'
                ])) {
                    throw new InvalidPartialResourceFieldsException("Cannot request period_goals and $field field", 102);
                }
            }
        }
    }
}
