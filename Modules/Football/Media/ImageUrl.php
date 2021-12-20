<?php

declare(strict_types=1);

namespace Module\Football\Media;

use InvalidArgumentException;
use Illuminate\Validation\Concerns\ValidatesAttributes;

final class ImageUrl
{
    use ValidatesAttributes;

    public function __construct(private string $url)
    {
        $this->validate();
    }

    private function validate(): void
    {
        if (!$this->validateUrl('', $this->url)) {
            throw new InvalidArgumentException('Invalid url ' . $this->url);
        }

        if ($host = parse_url($this->url, PHP_URL_HOST) !== env('PHOTOS_URL')) {
            throw new InvalidArgumentException('Invalid host url ' . $host, 444);
        }
    }

    public function toString(): string
    {
        return $this->url;
    }
}
