<?php

declare(strict_types=1);

namespace Module\User\Favourites;

use Module\User\QueryFields;
use App\Utils\PaginationData;
use Module\User\ValueObjects\UserId;
use Module\User\Collections\UserIdsCollection;
use Module\User\Exceptions\UserNotFoundHttpException;
use Module\User\Contracts\FetchUsersRepositoryInterface;
use Module\User\Exceptions\PrivateUserProfileHttpException;

final class FetchUserFavourites
{
    public function __construct(
        private FetchUsersRepositoryInterface $userRepository,
        private FetchUserFavouritesResourcesInterface $userFavouritesResources,
    ) {
    }

    public function get(UserId $userId, PaginationData $pagination): FavouritesResponse
    {
        return $this->userFavouritesResources->fetchResources($userId, $pagination);
    }

    /**
     * @throws UserNotFoundHttpException
     */
    public function FromRequest(UserFavouritesRequest $request): FavouritesResponse
    {
        $userId = UserId::fromRequest($request);

        $queryOptions = QueryFields::builder()->id()->isPrivate()->build();

        $usersCollection = $this->userRepository->findUsersById(UserIdsCollection::fromId($userId), $queryOptions);

        throw_if($usersCollection->isEmpty(), new UserNotFoundHttpException);
        throw_if($usersCollection->sole()->profileIsPrivate(), new PrivateUserProfileHttpException);

        return $this->get($userId, PaginationData::fromRequest($request));
    }
}
