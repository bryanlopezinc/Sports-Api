<?php

declare(strict_types=1);

namespace Module\User\Http\Controllers;

use Module\User\Http\Resources\UserResource;
use Module\User\Services\UserProfileService;
use Module\User\Http\Requests\UserProfileRequest;
use Module\User\Http\Resources\AuthUserProfileResource;
use Module\User\ValueObjects\UserId;

final class UserProfileController
{
    public function guest(UserProfileRequest $request, UserProfileService $service): UserResource
    {
        return new UserResource($service->FromRequest($request));
    }

    public function auth(UserProfileService $service): UserResource
    {
        return new AuthUserProfileResource($service->findById(UserId::fromAuthUser())->sole());
    }
}
