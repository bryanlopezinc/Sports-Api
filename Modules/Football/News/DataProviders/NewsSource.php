<?php

declare(strict_types=1);

namespace Module\Football\News\DataProviders;

use Illuminate\Contracts\Cache\Repository;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;
use Module\Football\News\NewsArticle;
use Module\Football\ValueObjects\Name;
use Psr\Log\LoggerInterface;

abstract class NewsSource
{
    abstract protected function cacheKey(): string;

    abstract protected function getTitleFrom(\DOMElement $domElement): ?string;

    abstract protected function getUrlFrom(\DOMElement $domElement): ?string;

    abstract protected function getBodyFrom(\DOMElement $domElement): ?string;

    abstract protected function queryExpression(): string;

    public function __construct(protected readonly Repository $cache, protected readonly LoggerInterface $logger)
    {
    }

    protected function mapHtmlResponse(Response $response): array
    {
        libxml_use_internal_errors(true);

        $document = new \DOMDocument();
        $document->loadHTML($response->body());

        $xpath = new \DOMXPath($document);

        $callback = function (\DOMElement $domElement) use ($response): NewsArticle|bool {
            $url = $this->getUrlFrom($domElement);
            $title = $this->getTitleFrom($domElement);
            $body = $this->getBodyFrom($domElement);

            if (!$this->isValidTitle($title) || !$this->isValidUrl($url)) {
                $this->logError(['title' => $title, 'url' => $response->effectiveUri()], $response->effectiveUri());

                return false;
            }

            return new NewsArticle($url, new Name($title), $body);
        };

        $articles = collect($xpath->query($this->queryExpression()))->map($callback);

        //If the articles collection is empty, that means the parent selector has changed and
        //stale data would be returned until the code is fixed/updated.
        if ($articles->isEmpty()) {
            $this->logError(['Could not find child attrbutes for query expression ' . $this->queryExpression()], $response->effectiveUri());

            return $this->getStaleData();
        }

        //Return stale data until the code is fixed/updated
        //when there is a change in child attributes
        if ($articles->filter()->isEmpty()) {
            return $this->getStaleData();
        }

        return $articles->tap(fn (Collection $collection) => $this->cache->forever($this->cacheKey(), $collection->all()))->all();
    }

    /**
     * @return array<NewsArticle>
     */
    protected function getStaleData(): array
    {
        return $this->cache->get($this->cacheKey(), []);
    }

    protected function isValidUrl(?string $url): bool
    {
        return !is_null($url);
    }

    protected function isValidTitle(?string $title): bool
    {
        return !is_null($title);
    }

    protected function logError(array $context, \Stringable|string $url): void
    {
        $this->logger->emergency("Error parsing response from " . $url, $context);
    }
}
