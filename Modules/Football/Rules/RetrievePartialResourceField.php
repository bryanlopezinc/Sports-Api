<?php

declare(strict_types=1);

namespace Module\Football\Rules;

use Module\Football\Exceptions\InvalidPartialResourceFieldsException;

trait RetrievePartialResourceField
{
    protected function getValue(mixed $value): string
    {
        if (!is_string($value)) {
            throw new InvalidPartialResourceFieldsException('The given data is not a valid format');
        }

        return $value;
    }
}
