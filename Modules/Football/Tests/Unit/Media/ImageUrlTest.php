<?php

declare(strict_types=1);

namespace Module\Football\Tests\Unit\Media;

use Tests\TestCase;
use Module\Football\Media\ImageUrl;

class ImageUrlTest extends TestCase
{
    public function test_url_must_be_valid(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new ImageUrl('foo+bar');
    }

    public function test_url_host_must_be_photos_host_url(): void
    {
        $this->expectExceptionCode(444);

        new ImageUrl('https://www.google.com/search?q=php+laravel');
    }
}
