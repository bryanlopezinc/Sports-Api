<?php

declare(strict_types=1);

namespace Module\User\Services;

use Module\User\QueryFields;
use App\Utils\PaginationData;
use Module\User\ValueObjects\UserId;
use Module\User\UserFavouritesResponse;
use Module\User\Collections\UserIdsCollection;
use Module\User\Http\Requests\UserFavouritesRequest;
use Module\User\Exceptions\UserNotFoundHttpException;
use Module\User\Contracts\FetchUsersRepositoryInterface;
use Module\User\Exceptions\PrivateUserProfileHttpException;
use Module\User\Contracts\FetchUserFavouritesRepositoryInterface;
use Module\User\Contracts\FetchUserFavouritesResourcesRepositoryInterface;

final class FetchUserFavourites
{
    public function __construct(
        private FetchUsersRepositoryInterface $userRepository,
        private FetchUserFavouritesRepositoryInterface $favouritesRepository,
        private FetchUserFavouritesResourcesRepositoryInterface $userFavouritesResources,
    ) {
    }

    public function get(UserId $userId, PaginationData $pagination): UserFavouritesResponse
    {
        $userFavouritesTypes = $this->favouritesRepository->getFavourites($userId, $pagination);

        $userfavouritesCollection = $this->userFavouritesResources->fetch($userFavouritesTypes->getCollection());

        return new UserFavouritesResponse($userfavouritesCollection, $userFavouritesTypes->getPagination()->hasMorePages());
    }

    /**
     * @throws UserNotFoundHttpException
     */
    public function FromRequest(UserFavouritesRequest $request): UserFavouritesResponse
    {
        $userId = UserId::fromRequest($request);

        $queryOptions = QueryFields::builder()->id()->isPrivate()->build();

        $usersCollection = $this->userRepository->findUsersById(UserIdsCollection::fromId($userId), $queryOptions);

        throw_if($usersCollection->isEmpty(), new UserNotFoundHttpException);

        throw_if($usersCollection->sole()->profileIsPrivate(), new PrivateUserProfileHttpException);

        return $this->get($userId, PaginationData::fromRequest($request));
    }
}
