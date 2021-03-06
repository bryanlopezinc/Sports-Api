<?php

declare(strict_types=1);

namespace App\ValueObjects;

use Illuminate\Http\Request;
use App\Exceptions\InvalidResourceIdException;
use App\HashId\HashIdInterface;

class ResourceId
{
    public function __construct(protected int $id)
    {
        $this->validate();
    }

    protected function validate(): void
    {
        throw_if($this->id < 1, new InvalidResourceIdException("invalid " . class_basename($this) . ' ' . $this->id));
    }

    public function toInt(): int
    {
        return $this->id;
    }

    public function asHashedId(): string
    {
        /** @var HashIdInterface */
        $hasher = app(HashIdInterface::class);

        return $hasher->hash($this->id);
    }

    protected static function getIdFromRequest(Request $request = null, string $key = 'id'): int
    {
        $request = $request ?: request();

        $exception = new \RuntimeException("Could not retrieve resource id with name {$key} from request");

        return (int) $request->input($key, fn () => throw $exception);
    }

    protected function equalsId(ResourceId $resourceId): bool
    {
        return $this->toInt() === $resourceId->toInt();
    }
}
