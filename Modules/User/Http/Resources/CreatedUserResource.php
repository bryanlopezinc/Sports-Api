<?php

declare(strict_types=1);

namespace Module\User\Http\Resources;

use JsonSerializable;
use Module\User\Routes\RouteName;

final class CreatedUserResource extends UserResource
{
    /**
     * {@inheritdoc}
     */
    public function withResponse($request, $response)
    {
        $response->setStatusCode(201);
    }

    protected function favouritesLink(): string|JsonSerializable
    {
        return route(RouteName::AUTH_USER_FAVOURITES);
    }

    protected function linkToSelf(): string|JsonSerializable
    {
        return route(RouteName::AUTH_USER_PROFILE);
    }
}
