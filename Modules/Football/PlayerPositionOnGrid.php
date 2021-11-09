<?php

declare(strict_types=1);

namespace Module\Football;

/**
 * Represents a player position on a teamlineUp grid view
 */
final class PlayerPositionOnGrid
{
    public function __construct(private ?int $row, private ?int $column)
    {
    }

    public function row(): int
    {
        return $this->row;
    }

    public function column(): int
    {
        return $this->column;
    }

    public function isNull(): bool
    {
        return is_null($this->row) && is_null($this->column);
    }
}
