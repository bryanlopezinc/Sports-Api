<?php

declare(strict_types=1);

namespace Module\Football\Media;

final class StreamGetContentsHttpClient implements FetchImageHttpClientInterface
{
    public function response(string $url): string
    {
        return stream_get_contents(fopen($url, 'r'));
    }
}
