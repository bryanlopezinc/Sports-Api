<?php

declare(strict_types=1);

namespace Module\User\Services;

use Module\User\QueryFields;
use App\Utils\PaginationData;
use Illuminate\Pagination\Paginator;
use Module\User\ValueObjects\UserId;
use Module\User\Repository\UserRepository;
use Module\Football\Prediction\UserPrediction;
use Module\User\Exceptions\UserNotFoundHttpException;
use Module\User\Http\Requests\FetchUserPredictionsRequest;
use Module\Football\Prediction\FetchUserPredictionsRepository;
use Module\User\Exceptions\PrivateUserProfileHttpException;

final class FetchUserPredictionsService
{
    public function __construct(
        private FetchUserPredictionsRepository $repository,
        private UserRepository $userRepository
    ) {
    }

    /**
     * @return Paginator<UserPrediction>
     */
    public function __invoke(UserId $userId, PaginationData $pagination): Paginator
    {
        return $this->repository->fetchUserPredictions($userId, $pagination);
    }

    /**
     * @return Paginator<UserPrediction>
     */
    public function forAuthUser(FetchUserPredictionsRequest $request): Paginator
    {
        return $this(UserId::fromAuthUser(), $this->getPaginationDataFrom($request));
    }

    /**
     * @return Paginator<UserPrediction>
     */
    public function forGuestUser(FetchUserPredictionsRequest $request): Paginator
    {
        $userId = UserId::fromRequest($request);

        $collection = $this->userRepository->findUsersById($userId->asCollection(), QueryFields::builder()->isPrivate()->build());

        if ($collection->isEmpty()) {
            throw new UserNotFoundHttpException;
        }

        if ($collection->sole()->profileIsPrivate()) {
            throw new PrivateUserProfileHttpException;
        }

        return $this($userId, $this->getPaginationDataFrom($request));
    }

    private function getPaginationDataFrom(FetchUserPredictionsRequest $request): PaginationData
    {
        return new PaginationData(
            $request->input('page', 1),
            $request->input('per_page', $request::MAX_PER_PAGE),
            $request::MAX_PER_PAGE
        );
    }
}
