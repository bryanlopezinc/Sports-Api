<?php

declare(strict_types=1);

namespace Module\Football\News\DataProviders\Goal;

use Module\Football\News\DataProviders\NewsSource;

abstract class GoalNews extends NewsSource
{
    protected function getTitleFrom(\DOMElement $domElement): ?string
    {
        return $domElement->getElementsByTagName('h3')->item(0)?->nodeValue;
    }

    protected function getBodyFrom(\DOMElement $domElement): ?string
    {
        return '';
    }

    protected function getUrlFrom(\DOMElement $domElement): ?string
    {
        $url = $domElement->childNodes->item(1)?->attributes->item(1)?->nodeValue;

        if (!$url) {
            return $url;
        }

        if (parse_url($url, PHP_URL_HOST) === null) {
            $url = 'https://www.goal.com' . $url;
        }

        return $url;
    }

    protected function isValidUrl(?string $url): bool
    {
        if (!parent::isValidUrl($url)) {
            return false;
        }

        return str_starts_with($url, "https://www.goal.com/en");
    }

    protected function queryExpression(): string
    {
        return "//article[@itemprop='itemListElement']";
    }
}
