<?php

declare(strict_types=1);

namespace Module\User\Clients\Favourites;

use Illuminate\Http\Client\Response;
use Module\User\Collections\UserFavouriteTypesCollection;

interface FetchFavouritesResourcesInterface
{
    /**
     * Each key in request is used as a response key in the responses array.
     * 
     * @return array<string, Request>
     */
    public function getRequestObjectsFrom(UserFavouriteTypesCollection $collection): array;

    /**
     * @param array<string, Response> $response
     */
    public function mapResponsesToDto(array $response): array;
}
