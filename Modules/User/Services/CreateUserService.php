<?php

declare(strict_types=1);

namespace Module\User\Services;

use Module\User\Dto\User;
use Illuminate\Contracts\Hashing\Hasher;
use Module\User\Dto\Builders\UserBuilder;
use Module\User\Http\Requests\CreateUserRequest;
use Module\User\Contracts\CreateUserRepositoryInterface;

final class CreateUserService
{
    public function __construct(private CreateUserRepositoryInterface $repository, private Hasher $hasher)
    {
    }

    public function fromRequest(CreateUserRequest $request): User
    {
        $user = (new UserBuilder)
            ->setEmail($request->input('email'))
            ->setIsPrivate($request->boolean('is_private'))
            ->setName($request->input('name'))
            ->setPassword($this->hasher->make($request->input('password')))
            ->setUsername($request->input('username'))
            ->build();

        return $this->repository->create($user);
    }
}
