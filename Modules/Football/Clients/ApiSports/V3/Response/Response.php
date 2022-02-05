<?php

declare(strict_types=1);

namespace Module\Football\Clients\ApiSports\V3\Response;

use ErrorException;
use Illuminate\Support\Arr;

class Response
{
    public function __construct(protected readonly array $data)
    {
    }

    /**
     * Determine if a key exits in the response data using 'dot' notation
     */
    public function has(string $key): bool
    {
        return Arr::has($this->data, $key);
    }

    /**
     * Get a key from the response data using 'dot' notation
     *
     * @throws ErrorException
     */
    public function get(string $key): mixed
    {
        return Arr::get($this->data, $key, fn () => throw new ErrorException("Undefined key {$key}"));
    }
}
