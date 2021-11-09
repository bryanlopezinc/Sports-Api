<?php

declare(strict_types=1);

namespace Module\Football\ValueObjects;

use LogicException;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;

final class TeamFormation
{
    /**
     * @param array<int> $formation
     */
    private function __construct(private array $formation)
    {
        $this->validate();
    }

    public static function fromString(string $formation, string $seperator = '-'): static
    {
        return Str::of($formation)
            ->explode($seperator)
            ->map(fn (string $numberOfPlayers): int => (int) $numberOfPlayers)
            ->pipe(fn (Collection $collection): self => new static($collection->all()));
    }

    private function validate(): void
    {
        if (!preg_match("/^[1-9-]+(-[1-9]+)+$/", $this->toString())) {
            $this->throwException(400);
        }

        if (array_sum($this->formation) !== 10) {
            $this->throwException(401);
        }
    }

    public function toString(): string
    {
        return implode('-', $this->formation);
    }

    private function throwException(string|int $code): void
    {
        throw new LogicException('Invalid team formation ' . implode(' ', $this->formation), $code);
    }
}
