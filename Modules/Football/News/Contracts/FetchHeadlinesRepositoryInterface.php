<?php

declare(strict_types=1);

namespace Module\Football\News\Contracts;

use Module\Football\News\NewsArticle;

interface FetchHeadlinesRepositoryInterface
{
    /**
     * @return array<NewsArticle>
     */
    public function headlines(): array;
}