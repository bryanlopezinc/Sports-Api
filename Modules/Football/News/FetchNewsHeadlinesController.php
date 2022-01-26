<?php

declare(strict_types=1);

namespace Module\Football\News;

use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Module\Football\News\Contracts\FetchHeadlinesRepositoryInterface;

final class FetchNewsHeadlinesController
{
    public function __invoke(FetchHeadlinesRepositoryInterface $repository): AnonymousResourceCollection
    {
        return NewsArticleResource::collection($repository->headlines());
    }
}
