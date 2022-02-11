<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\AnonymousResourceCollection as JsonAnonymousResourceCollection;
use Illuminate\Pagination\Paginator;

final class PaginatedResourceCollection extends JsonAnonymousResourceCollection
{
    public function __construct(private Paginator $paginator, string $collects)
    {
        parent::__construct($paginator, $collects);
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
}
