<?php

declare(strict_types=1);

namespace App\Utils;

use Illuminate\Http\Request;

/**
 * Pagination data for multiple resource request
 */
final class PaginationData
{
    /**
     * Default maximum items to be returned per page.
     * Restricts requesting large amount of data
     */
    public const MAX_PER_PAGE = 30;

    /** Default minimum items that can be returned per page */
    public const MIN_PER_PAGE = 15;

    /** Default maximum page that can be requested */
    public const MAX_PAGE = 5000;

    public function __construct(
        private int $page = 1,
        private int $perPage = self::MAX_PER_PAGE,
        private int $maxItemsPerPage = self::MAX_PER_PAGE,
        private int $minItemsPerPage = self::MIN_PER_PAGE
    ) {
    }

    public static function fromRequest(Request $request = null, string $pageKey = 'page', string $perPageKey = 'per_page'): static
    {
        $request = $request ?: request();

        return new static(
            (int) $request->input($pageKey, 1),
            (int) $request->input($perPageKey, self::MAX_PER_PAGE)
        );
    }

    public function getPerPage(): int
    {
        if ($this->perPage > $this->maxItemsPerPage) {
            return $this->maxItemsPerPage;
        }

        if ($this->perPage < $this->minItemsPerPage) {
            return $this->minItemsPerPage;
        }

        return $this->perPage;
    }

    public function getPage(): int
    {
        if ($this->page < 1) {
            return 1;
        }

        return $this->page > self::MAX_PAGE ? 1 : $this->page;
    }
}
