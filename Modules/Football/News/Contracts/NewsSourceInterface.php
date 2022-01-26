<?php

declare(strict_types=1);

namespace Module\Football\News\Contracts;

use Illuminate\Http\Client\Pool;
use Illuminate\Http\Client\Response;
use Module\Football\News\NewsArticle;

interface NewsSourceInterface
{
    /**
     * @return \GuzzleHttp\Promise\Promise
     */
    public function configure(Pool $pool);

    /**
     * @param array<string|int,Response> $response
     * Each key in the array is the request alias used when sending the request.
     *
     * @return array<NewsArticle>
     */
    public function toNewsArticles(array $response): array;
}
