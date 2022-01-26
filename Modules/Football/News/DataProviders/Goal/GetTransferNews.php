<?php

declare(strict_types=1);

namespace Module\Football\News\DataProviders\Goal;

use Illuminate\Http\Client\Pool;
use Module\Football\News\Contracts\NewsSourceInterface;

final class GetTransferNews extends GoalNews implements NewsSourceInterface
{
    public const ALIAS = 'transferNewsfromGoalWebsite';

    protected function cacheKey(): string
    {
        return self::ALIAS;
    }

    protected function url(): string
    {
        return 'https://www.goal.com/en/transfer-news';
    }

    /**
     * {@inheritdoc}
     */
    public function configure(Pool $pool)
    {
        return $pool
            ->as(self::ALIAS)
            ->withUserAgent('Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/97.0.4692.71 Safari/537.36')
            ->accept('text/html')
            ->get($this->url());
    }

    /**
     * {@inheritdoc}
     */
    public function toNewsArticles(array $responses): array
    {
        $response = $responses[self::ALIAS];

        if (!$response->successful()) {
            $this->logger->emergency('Request failed for ' . $this->url(), [
                'statusCode' => $response->status()
            ]);

            return $this->getStaleData();
        }

        return array_slice($this->mapHtmlResponse($response), 0, 20);
    }
}
