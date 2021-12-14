<?php

declare(strict_types=1);

namespace Module\User\Favourites\Clients;

use Illuminate\Http\Client\Response;
use Module\User\Favourites\FavouritesCollection;

interface FavouritesResolverInterface
{
    /**
     * Each key in request is used as a response key in the responses array.
     *
     * @return array<string, Request>
     */
    public function getRequestObjectsFrom(FavouritesCollection $collection): array;

    /**
     * @param array<string, Response> $response
     */
    public function mapResponsesToDto(array $response): array;
}
