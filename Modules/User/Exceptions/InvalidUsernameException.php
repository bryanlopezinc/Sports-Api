<?php

declare(strict_types=1);

namespace Module\User\Exceptions;

final class InvalidUsernameException extends \Exception
{
    public const LENGTH_EXCEEDED = 422;
    public const LENGTH_TOO_LOW  = 433;
    public const INVALID_CHARS   = 434;

    public static function lengthExceded(int $maxLength): self
    {
        return new static('Username must not be greater than ' . $maxLength, self::LENGTH_EXCEEDED);
    }

    public static function minLength(int $minLength): self
    {
        return new static('Username must not be Less than ' . $minLength, self::LENGTH_TOO_LOW);
    }

    public static function regex(): self
    {
        return new static('Username should contain only Alphabets, Number and Underscores', self::INVALID_CHARS);
    }
}
