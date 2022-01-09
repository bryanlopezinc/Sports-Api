<?php

declare(strict_types=1);

namespace Module\Football\Rules;

use Illuminate\Contracts\Validation\Rule;
use Module\Football\Exceptions\InvalidPartialResourceFieldsException;

final class PartialFixturePlayersStatisticsFieldsRule implements Rule
{
    private const ALLOWED = [
        'team',
        'rating',
        'minutes_played',
        'offsides',
        'interception',
        'cards',
        'cards.yellow',
        'cards.red',
        'cards.total',
        'dribbles',
        'dribbles.attempts',
        'dribbles.successful',
        'dribbles.past',
        'goals',
        'goals.total',
        'goals.assists',
        'goals.saves',
        'goals.conceeded',
        'shots',
        'shots.on_target',
        'shots.total',
        'passes',
        'passes.accuracy',
        'passes.key',
        'passes.total'
    ];

    private string $message;

    /**
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (!is_string($value)) {
            $this->message = "The $attribute field must be a string";

            return false;
        }

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

    /**
     * @param array<string> $requestedFields
     */
    private function validate(array $requestedFields): void
    {
        $parentChildrenMap = [
            'cards'     => ['cards.yellow', 'cards.red', 'cards.total'],
            'dribbles'  => ['dribbles.past', 'dribbles.successful', 'dribbles.attempts'],
            'goals'     => ['goals.total', 'goals.assists', 'goals.saves', 'goals.conceeded'],
            'shots'     => ['shots.on_target', 'shots.total'],
            'passes'    => ['passes.key', 'passes.accuracy', 'passes.total']
        ];

        foreach ($requestedFields as $field) {
            if (notInArray($field, self::ALLOWED)) {
                throw new InvalidPartialResourceFieldsException("The given partial resource field $field is Invalid");
            }
        }

        //Ensure that a parent key and a child key are not requested at the same time
        //E.g cards and any of cards.yellow, cards.red and cards.total etc, is considered invalid
        foreach ($parentChildrenMap as $parent => $children) {

            //If parent is not in the requested fields
            //it is valid to have any of its children
            if (notInArray($parent, $requestedFields)) {
                continue;
            }

            foreach ($children as $child) {
                if (inArray($child, $requestedFields)) {
                    throw new InvalidPartialResourceFieldsException(
                        $this->errorMessageForParentChildRequest($parent, $child)
                    );
                }
            }
        }
    }

    public function errorMessageForParentChildRequest(string $parent, string $child): string
    {
        return sprintf('%s and %s cannot be requested at the same time', $parent, $child);
    }
}
