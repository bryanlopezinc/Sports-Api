<?php

declare(strict_types=1);

namespace Module\Football\Http\Controllers;

use Illuminate\Pagination\Paginator;
use Module\Football\Http\Resources\CommentResource;
use Module\Football\Services\FetchFixtureCommentsService;
use Module\Football\Http\Requests\FetchFixtureCommentsRequest;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

final class FetchFixtureCommentsController
{
    public function __invoke(FetchFixtureCommentsRequest $request, FetchFixtureCommentsService $service): AnonymousResourceCollection
    {
        return $this->resourceCollection($service->fromRequest($request));
    }

    private function resourceCollection(Paginator $paginator): AnonymousResourceCollection
    {
        return new class($paginator) extends AnonymousResourceCollection
        {
            public function __construct(private Paginator $paginator)
            {
                parent::__construct($paginator, CommentResource::class);
            }

            /**
             * Get rid of null values
             */
            public function paginationInformation($request, $paginated, array $default)
            {
                unset(
                    $default['links']['last'],
                    $default['meta']['from'],
                    $default['meta']['to'],
                );

                $default['meta']['has_more_pages'] = $this->paginator->hasMorePages();

                if (!$this->paginator->hasMorePages()) {
                    unset($default['links']['next']);
                }

                if ($this->paginator->currentPage() === 1) {
                    $default['links']['prev'] = $default['links']['first'];
                }

                return $default;
            }
        };
    }
}
