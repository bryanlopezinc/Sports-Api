<?php

declare(strict_types=1);

namespace Module\User\ValueObjects;

use Stringable;
use App\Utils\Config;
use JsonSerializable;
use Module\User\Exceptions\InvalidUsernameException;

final class Username implements JsonSerializable, Stringable
{
    public function __construct(private string $username)
    {
        $this->validate();
    }

    public static function fromString(string $username): self
    {
        return new static($username);
    }

    private function validate(): void
    {
        $maxLength = Config::get('user.usernameMaxLength');
        $minLength = Config::get('user.usernameMinLength');
        $usernameLength = mb_strlen($this->username);

        if ($usernameLength > $maxLength) {
            throw InvalidUsernameException::lengthExceded($maxLength);
        }

        if ($usernameLength < $minLength) {
            throw InvalidUsernameException::minLength($minLength);
        }

        if (!preg_match('/^[A-Za-z0-9_]+$/', $this->username)) {
            throw InvalidUsernameException::regex();
        }
    }

    public function toString(): string
    {
        return $this->username;
    }

    public function __toString()
    {
        return $this->toString();
    }

    public function jsonSerialize()
    {
        return $this->toString();
    }
}
