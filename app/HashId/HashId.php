<?php

declare(strict_types=1);

namespace App\HashId;

use Hashids\Hashids;
use App\ValueObjects\PositiveNumber;

final class HashId implements HashIdInterface
{
    private Hashids $hashIds;

    public function __construct(string $salt, int $minLength)
    {
        $this->hashIds = new Hashids($salt, $minLength);
    }

    public function hash(int $id): string
    {
        PositiveNumber::check($id);

        return $this->hashIds->encode($id);
    }

    public function decode(string $hash): int
    {
        $decoded = $this->hashIds->decode($hash);

        if (empty($decoded)) {
            throw new CannotDecodeHashIdException();
        }

        return $decoded[0];
    }
}
