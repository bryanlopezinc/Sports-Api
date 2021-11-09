<?php

declare(strict_types=1);

namespace Module\User\Http\Controllers;

use Module\User\Http\Resources\UserResource;
use Module\User\Services\UserProfileService;
use Module\User\Http\Requests\UserProfileRequest;

final class UserProfileController
{
    public function __invoke(UserProfileRequest $request, UserProfileService $service): UserResource
    {
        return new UserResource($service->FromRequest($request));
    }
}
