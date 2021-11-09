<?php

declare(strict_types=1);

namespace Module\Football\ValueObjects;

use InvalidArgumentException;

final class TeamForm
{
    public const WIN   = 'win';
    public const LOOSE = 'loose';
    public const DRAW  = 'draw';

    /**
     * @param array<string> $forms
     */
    public function __construct(private array $forms)
    {
        $this->validate();
    }

    private function validate(): void
    {
        foreach ($this->forms as $form) {
            if (notInArray($form, [self::WIN, self::LOOSE, self::DRAW])) {
                throw new InvalidArgumentException('invalid team form value ' . $form);
            }
        }
    }

    /**
     * @return array<string>
     */
    public function toArray(): array
    {
        return $this->forms;
    }
}
