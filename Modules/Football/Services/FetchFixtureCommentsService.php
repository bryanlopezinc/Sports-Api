<?php

declare(strict_types=1);

namespace Module\Football\Services;

use App\Utils\PaginationData;
use Illuminate\Pagination\Paginator;
use Module\Football\ValueObjects\FixtureId;
use Module\Football\Repository\CommentsRepository;
use Module\Football\Collections\FixtureIdsCollection;
use App\Exceptions\Http\ResourceNotFoundHttpException;
use Module\Football\Http\Requests\FetchFixtureCommentsRequest;

final class FetchFixtureCommentsService
{
    public function __construct(private FetchFixtureService $service, private CommentsRepository $repository)
    {
    }

    /**
     * @throws ResourceNotFoundHttpException
     */
    public function fromRequest(FetchFixtureCommentsRequest $request): Paginator
    {
        $id = FixtureId::fromRequest($request);

        $fixtureExists = $this->service->findMany(new FixtureIdsCollection([$id]))->isNotEmpty();

        if (!$fixtureExists) {
            throw new ResourceNotFoundHttpException;
        }

        return $this->repository->getFixtureComments($id, PaginationData::fromRequest($request));
    }
}
