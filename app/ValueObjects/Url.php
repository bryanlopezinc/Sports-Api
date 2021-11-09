<?php

declare(strict_types=1);

namespace App\ValueObjects;

use InvalidArgumentException;
use Illuminate\Support\Facades\Validator;

final class Url
{
    public function __construct(private string $url)
    {
        $this->validate();
    }

    public static function fromString(string $url): static
    {
        return new static($url);
    }

    protected function validate(): void
    {
        if (Validator::make(['value' => $this->url], ['value' => 'url'])->fails()) {
            throw new InvalidArgumentException('Invalid url '. $this->url);
        }
    }

    public function url(): string
    {
        return $this->url;
    }

    public function __toString()
    {
        return $this->url();
    }
}
