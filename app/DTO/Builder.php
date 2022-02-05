<?php

declare(strict_types=1);

namespace App\DTO;

use Illuminate\Contracts\Support\Arrayable;

abstract class Builder implements Arrayable
{
    /**
     * @param array<mixed> $attributes
     */
    public function __construct(protected array $attributes = [])
    {
    }

    /**
     * if the value is a callback the instance of the builder is passed as its arguement
     */
    public function when(bool $condtion, mixed $value): static
    {
        if (!$condtion) {
            return $this;
        }

        return tap($this, fn () => value($value, $this));
    }

    public function set(string $key, mixed $value): static
    {
        $this->attributes[$key] = $value;

        return $this;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray()
    {
        return $this->attributes;
    }
}
