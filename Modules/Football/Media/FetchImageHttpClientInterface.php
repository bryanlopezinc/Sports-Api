<?php

declare(strict_types=1);

namespace Module\Football\Media;

interface FetchImageHttpClientInterface
{
    /**
     * @return string Raw contents of image file
     */
    public function response(string $url): string;
}
