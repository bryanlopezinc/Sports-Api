<?php

declare(strict_types=1);

namespace Module\Football\Rules;

use Illuminate\Contracts\Validation\Rule;
use Module\Football\Exceptions\InvalidPartialResourceFieldsException;

final class LeagueFieldsRule implements Rule
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
    public int $code;

    /**
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        try {
            $this->validate($value);

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
            throw new InvalidPartialResourceFieldsException('Only id field cannot be requested', 2000);
        }

        foreach ($requestedFields as $field) {
            if (!inArray($field, self::ALLOWED)) {
                throw new InvalidPartialResourceFieldsException('The given partial resource fields are Invalid', 2001);
            }
        }

        $this->validateForParentAndChildren($requestedFields);
    }

    private function validateForParentAndChildren(array $requestedFields): void
    {
        $parentChildrenMap = [
            'season'     => [
                'season.season',
                'season.start',
                'season.end',
                'season.is_current_season',
            ],
            'coverage'  => [
                'coverage.line_up',
                'coverage.events',
                'coverage.stats',
                'coverage.top_scorers',
                'coverage.top_assists',
            ],
        ];

        //Ensure that a parent key and a child key are not requested at the same time
        //E.g season and any of season.start, season.end etc, is considered invalid
        foreach ($parentChildrenMap as $parent => $children) {

            //If parent is not in the requested fields
            //it is valid to have any of its children
            if (notInArray($parent, $requestedFields)) {
                continue;
            }

            foreach ($children as $child) {
                if (inArray($child, $requestedFields)) {
                    throw new InvalidPartialResourceFieldsException(
                        sprintf('Cannot request %s with %s attributes', $parent, $child),
                        2002
                    );
                }
            }
        }
    }
}
