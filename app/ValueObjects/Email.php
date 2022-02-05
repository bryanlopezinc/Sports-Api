<?php

declare(strict_types=1);

namespace App\ValueObjects;

use InvalidArgumentException;
use Illuminate\Support\Facades\Validator;

final class Email
{
    private function __construct(private string $email)
    {
        $this->validate();
    }

    public static function fromString(string $email): static
    {
        return new static($email);
    }

    private function validate(): void
    {
        if (Validator::make(['value' => $this->email], ['value' => 'email'])->fails()) {
            throw new InvalidArgumentException('Invalid email ' . $this->email);
        }
    }

    public function toString(): string
    {
        return $this->email;
    }
}
