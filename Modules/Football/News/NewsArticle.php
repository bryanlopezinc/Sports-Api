<?php

declare(strict_types=1);

namespace Module\Football\News;

use Module\Football\ValueObjects\Name;

final class NewsArticle
{
    public function __construct(
        public readonly string $linkToFullArticle,
        public readonly Name $title,
        public readonly string $shortDescription
    ) {
        throw_if(filter_var($linkToFullArticle, FILTER_VALIDATE_URL) === false, new \InvalidArgumentException());
    }
}
