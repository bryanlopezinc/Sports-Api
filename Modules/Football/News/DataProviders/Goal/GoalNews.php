<?php

declare(strict_types=1);

namespace Module\Football\News\DataProviders\Goal;

use Illuminate\Contracts\Cache\Repository;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;
use Module\Football\News\NewsArticle;
use Module\Football\ValueObjects\Name;
use Psr\Log\LoggerInterface;

abstract class GoalNews
{
    abstract protected function url(): string;

    abstract protected function cacheKey(): string;

    public function __construct(protected readonly Repository $cache, protected readonly LoggerInterface $logger)
    {
    }

    protected function mapHtmlResponse(Response $response): array
    {
        libxml_use_internal_errors(true);

        $document = new \DOMDocument();
        $document->loadHTML($response->body());

        $xpath = new \DOMXPath($document);

        $callback = function (\DOMElement $domElement): NewsArticle|bool {
            $url = $domElement->childNodes->item(1)?->attributes->item(1)?->nodeValue;
            $title = $domElement->getElementsByTagName('h3')->item(0)?->nodeValue;

            if (!$this->isValidTitle($title) || !$this->isValidUrl($url)) {
                $this->logError(['title' => $title, 'url' => $url]);

                return false;
            }

            return new NewsArticle('https://www.goal.com/' . $url, new Name($title), '');
        };

        $articles = collect($xpath->query("//article[@itemprop='itemListElement']"))->map($callback);

        //If the articles collection is empty, that means the parent selector has changed and
        //stale data would be returned until the code is fixed/updated.
        if ($articles->isEmpty()) {
            $this->logError(['Could not find child attrbutes for query expression']);

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
        return is_null($url) ? false : str_starts_with($url, "/en/");
    }

    protected function isValidTitle(?string $title): bool
    {
        return !is_null($title);
    }

    protected function logError(array $context): void
    {
        $this->logger->emergency("Error parsing response from " . $this->url(), $context);
    }
}
