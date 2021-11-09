<?php

declare(strict_types=1);

namespace Module\User\Http\Controllers;

use Module\User\Dto\Builders\UserBuilder;
use Module\User\Services\UserProfileService;
use Module\User\Http\Requests\UserProfileRequest;
use Module\User\Http\Resources\AuthUserProfileResource;

final class MyProfileController
{
    public function __invoke(UserProfileRequest $request, UserProfileService $service): AuthUserProfileResource
    {
        return new AuthUserProfileResource($service->findById(UserBuilder::fromAuthUser()->build()->getId())->sole());
    }
}
