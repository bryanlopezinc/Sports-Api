<?php

declare(strict_types=1);

namespace Module\Football\News\DataProviders\SkySports;

use DOMElement;
use Illuminate\Http\Client\Pool;
use Module\Football\News\Contracts\NewsSourceInterface;
use Module\Football\News\DataProviders\NewsSource;

final class LatestNews extends NewsSource implements NewsSourceInterface
{
    private const ALIAS = 'latestNewsFromSkySports';

    /**
     * {@inheritdoc}
     */
    public function configure(Pool $pool)
    {
        return $pool->as(self::ALIAS)->accept('text/html')->get('https://www.skysports.com/football/news');
    }

    /**
     * {@inheritdoc}
     */
    public function toNewsArticles(array $responseData): array
    {
        $response = $responseData[self::ALIAS];

        if (!$response->successful()) {
            $this->logger->emergency('Request failed for ' . $response->effectiveUri(), [
                'statusCode' => $response->status()
            ]);

            return $this->getStaleData();
        }

        return array_slice($this->mapHtmlResponse($response), 0, 20);
    }

    protected function getTitleFrom(DOMElement $domElement): ?string
    {
        $title = $domElement->getElementsByTagName('h4')->item(0)?->nodeValue;

        return is_null($title) ? $title : trim($title);
    }

    protected function getBodyFrom(DOMElement $domElement): ?string
    {
        $body = $domElement->getElementsByTagName('p')->item(0)?->nodeValue;

        return is_null($body) ? $body : trim($body);
    }

    protected function getUrlFrom(DOMElement $domElement): ?string
    {
        return collect($domElement->childNodes)
            ->filter(fn (\DOMNode $node) => $node->nodeName === 'a')
            ->map(fn (\DOMElement $element) => $element->getAttribute('href'))
            ->first();
    }

    protected function isValidUrl(?string $url): bool
    {
        if (!parent::isValidUrl($url)) {
            return false;
        }

        return parse_url($url, PHP_URL_HOST) === 'www.skysports.com';
    }

    protected function queryExpression(): string
    {
        return "//div[@class='news-list__item news-list__item--show-thumb-bp30']";
    }

    protected function cacheKey(): string
    {
        return self::ALIAS;
    }
}
