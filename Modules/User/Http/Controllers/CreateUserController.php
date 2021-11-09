<?php

declare(strict_types=1);

namespace Module\User\Http\Controllers;

use Module\User\Services\CreateUserService;
use Psr\Http\Message\ServerRequestInterface;
use Module\User\Http\Requests\CreateUserRequest;
use Module\User\Http\Resources\CreatedUserResource;
use Laravel\Passport\Http\Controllers\AccessTokenController;

final class CreateUserController extends AccessTokenController
{
    public function __invoke(CreateUserRequest $request, CreateUserService $service, ServerRequestInterface $serverRequest): CreatedUserResource
    {
        $user = $service->fromRequest($request);

        $tokenResponse = $this->issueToken($serverRequest);

        return (new CreatedUserResource($user))->additional(json_decode($tokenResponse->getContent(), true));
    }
}
