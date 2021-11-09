<?php

declare(strict_types=1);

namespace Module\User\Services;

use Module\User\Dto\User;
use Module\User\QueryFields;
use Module\User\ValueObjects\UserId;
use Module\User\Collections\UsersCollection;
use Module\User\Collections\UserIdsCollection;
use Module\User\Http\Requests\UserProfileRequest;
use Module\User\Exceptions\UserNotFoundHttpException;
use Module\User\Contracts\FetchUsersRepositoryInterface;
use Module\User\Exceptions\PrivateUserProfileHttpException;

final class UserProfileService
{
    public function __construct(private FetchUsersRepositoryInterface $repository)
    {
    }

    public function findById(UserId $userId): UsersCollection
    {
        return $this->repository->findUsersById(UserIdsCollection::fromId($userId), new QueryFields());
    }

    public function FromRequest(UserProfileRequest $request): User
    {
        $collection = $this->findById(UserId::fromRequest($request));

        throw_if($collection->isEmpty(), new UserNotFoundHttpException);

        throw_if($collection->sole()->profileIsPrivate(), new PrivateUserProfileHttpException);

        return $collection->sole();
    }
}
