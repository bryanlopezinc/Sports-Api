<?php

declare(strict_types=1);

namespace Module\Football\News\Repositories;

use GuzzleHttp\Promise\Promise;
use Illuminate\Http\Client\Pool;
use Illuminate\Support\Facades\Http;
use Module\Football\News\Contracts\NewsSourceInterface;
use Module\Football\News\Contracts\FetchHeadlinesRepositoryInterface;

final class FetchHeadlinesRepository implements FetchHeadlinesRepositoryInterface
{
    private const SOURCES = [
        \Module\Football\News\DataProviders\Goal\GetHeadlines::class,
        \Module\Football\News\DataProviders\Goal\GetTransferNews::class,
    ];

    public function headlines(): array
    {
        $sources = collect(self::SOURCES)->map(fn (string $pathToSource): NewsSourceInterface => app($pathToSource));

        $response = Http::pool(function (Pool $pool) use ($sources) {
            return $sources->map(fn (NewsSourceInterface $source): Promise => $source->configure($pool))->all();
        });

        return $sources->map(fn (NewsSourceInterface $source) => $source->toNewsArticles($response))->flatten()->shuffle()->all();
    }
}
