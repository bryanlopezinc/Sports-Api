<?php

declare(strict_types=1);

namespace App\HashId;

interface HashIdInterface
{
    /**
     * @param int $id A positive number
     *
     * @return string Alpha numeric string
     *
     * @throws \InvalidArgumentException
     */
    public function hash(int $id): string;

    /**
     * @throws CannotDecodeHashIdException
     */
    public function decode(string $hash): int;
}
