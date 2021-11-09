<?php

declare(strict_types=1);

namespace Module\User\Tests\Unit\ValueObjects;

use App\Utils\Config;
use Tests\TestCase;
use Illuminate\Support\Str;
use Module\User\ValueObjects\Username;
use Module\User\Exceptions\InvalidUsernameException;

class UsernameTest extends TestCase
{
    public function test_username_length_cannot_be_longer_than_expected(): void
    {
        $this->expectExceptionCode(InvalidUsernameException::LENGTH_EXCEEDED);

        new Username(Str::random(Config::get('user.usernameMaxLength') + 1));
    }

    public function test_username_length_cannot_be_shorter_than_expected(): void
    {
        $this->expectExceptionCode(InvalidUsernameException::LENGTH_TOO_LOW);

        new Username(Str::random(Config::get('user.usernameMinLength') - 1));
    }

    public function test_throws_exception_when_contains_invalid_chars(): void
    {
        $username = Str::random(Config::get('user.usernameMaxLength') - 1);

        foreach ([
            '`', '!', '@', '#', '$', '%', '^', '&', '*', '(', ')', '-', '=', '+', '{', '}', '[', ']', ':', '"', '<', '>', ',', '.', '/', '?', '|', ';', '\\',
        ] as $invalid) {
            $this->assertFalse($this->isValid($username . $invalid), 'Assertion failed for char ' . $invalid);
        }
    }

    private function isValid(string $username): bool
    {
        try {
            new Username($username);

            return true;
        } catch (InvalidUsernameException) {
            return false;
        }
    }
}
