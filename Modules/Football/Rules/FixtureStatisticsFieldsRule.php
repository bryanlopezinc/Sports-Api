<?php

declare(strict_types=1);

namespace Module\Football\Rules;

use Illuminate\Contracts\Validation\Rule;
use Module\Football\Exceptions\InvalidPartialResourceFieldsException;

final class FixtureStatisticsFieldsRule implements Rule
{
    use RetrieveResourceField;

    private const ALLOWED = [
        'shots_on_target',
        'shots_off_target',
        'shots_inside_box',
        'shots_outside_box',
        'shots',
        'blocked_shots',
        'fouls',
        'corners',
        'offsides',
        'yellow_cards',
        'red_cards',
        'keeper_saves',
        'passes',
        'accurate_passes',
        'ball_possession',
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
        foreach ($requestedFields as $field) {
            if (notInArray($field, self::ALLOWED)) {
                throw new InvalidPartialResourceFieldsException('The given partial resource fields are Invalid');
            }
        }
    }
}
