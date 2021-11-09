<?php

declare(strict_types=1);

namespace Module\User\Rules;

use App\Utils\Config;
use Module\User\ValueObjects\Username;
use Illuminate\Contracts\Validation\Rule;
use Module\User\Exceptions\InvalidUsernameException;

final class UsernameRule implements Rule
{
    private string $message;

    public function passes($attribute, $value)
    {
        try {
            new Username($value);

            return true;
        } catch (InvalidUsernameException $e) {
            $this->message = match ($e->getCode()) {
                $e::INVALID_CHARS     => 'The username contains invalid characters',
                $e::LENGTH_EXCEEDED   => 'The username should not be longer than ' . Config::get('user.usernameMaxLength'),
                $e::LENGTH_TOO_LOW    => 'The username should not be less than ' . Config::get('user.usernameMinLength'),
            };

            return false;
        }
    }

    public function message()
    {
        return $this->message;
    }
}
