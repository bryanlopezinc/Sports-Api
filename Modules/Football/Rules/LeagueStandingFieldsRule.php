<?php

declare(strict_types=1);

namespace Module\Football\Rules;

use Illuminate\Contracts\Validation\Rule;
use Module\Football\Exceptions\InvalidPartialResourceFieldsException;

final class LeagueStandingFieldsRule implements Rule
{
    use RetrieveResourceField;

    private const ALLOWED = [
        'points',
        'position',
        'team',
        'team_form',
        'played',
        'won',
        'lost',
        'draws',
        'home_record',
        'away_record',
        'goal_difference',
        'goals_found',
        'goals_against',
        'league'
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
            $this->validate(explode(',', $this->getValue($value)));

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
        //Only the TEAM field cannot be requested
        if (count($requestedFields) === 1 && $requestedFields[0] === 'team') {
            throw new InvalidPartialResourceFieldsException('Only team field cannot be requested');
        }

        foreach ($requestedFields as $field) {
            if (!inArray($field, self::ALLOWED)) {
                throw new InvalidPartialResourceFieldsException('The given partial resource fields are Invalid');
            }
        }
    }
}
