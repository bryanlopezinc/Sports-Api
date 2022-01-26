<?php

declare(strict_types=1);

namespace Module\User\Favourites\Clients;

use Illuminate\Http\Client\Pool;
use Illuminate\Pagination\Paginator;

interface RequestsFavouriteResourceInterface
{
    /**
     * @param array<string|int, Response> $response
     */
    public function toDataTransferObject(array $response): array;

    /**
     * @return array<\GuzzleHttp\Promise\Promise>
     */
    public function configure(Pool $pool, Paginator $favourites): array;
}
