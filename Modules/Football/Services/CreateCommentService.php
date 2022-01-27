<?php

declare(strict_types=1);

namespace Module\Football\Services;

use App\Exceptions\Http\ResourceNotFoundHttpException;
use Module\Football\ValueObjects\FixtureId;
use Module\Football\Collections\FixtureIdsCollection;
use Module\Football\Http\Requests\CreateCommentRequest;
use Module\Football\Repository\CommentsRepository;
use Module\Football\ValueObjects\Comment;
use Module\User\ValueObjects\UserId;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class CreateCommentService
{
    public function __construct(private FetchFixtureService $service, private CommentsRepository $repository)
    {
    }

    /**
     * @throws HttpException
     */
    public function fromRequest(CreateCommentRequest $request): void
    {
        $id = FixtureId::fromRequest($request, 'fixture_id');

        $fixtureExists = $this->service->findMany(new FixtureIdsCollection([$id]))->isNotEmpty();

        if (!$fixtureExists) {
            throw new ResourceNotFoundHttpException;
        }

        $this->repository->saveFixtureComment($id, new Comment($request->input('comment')), UserId::fromAuthUser());
    }
}
