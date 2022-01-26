<?php

declare(strict_types=1);

namespace Module\Football\Tests\Unit\News\DataProviders\Goal;

use Tests\TestCase;
use Illuminate\Contracts\Cache\Repository;
use Illuminate\Support\Facades\Http;
use Module\Football\News\DataProviders\Goal\GetTransferNews;
use Psr\Log\LoggerInterface;

class GetTransferNewsTest extends TestCase
{
    /**
     * @return array<\PHPUnit\Framework\MockObject\MockObject>
     */
    private function getMocks(): array
    {
        return [
            $this->getMockBuilder(Repository::class)->getMock(),
            $this->getMockBuilder(LoggerInterface::class)->getMock()
        ];
    }

    public function test_will_not_return_stale_data_and_log_error(): void
    {
        [$cache, $logger] = $this->getMocks();

        $cache->expects($this->never())->method('get')->willReturn([]);
        $logger->expects($this->never())->method('emergency');

        $this->swap(Repository::class, $cache);

        /** @var GetTransferNews */
        $provider = app(GetTransferNews::class);

        $provider->toNewsArticles([
            $provider::ALIAS => Http::fake(fn () => Http::response($this->rawHtml()))->get('https://google.com')
        ]);
    }

    public function test_will_return_stale_data_and_log_error_when_child_attributes_change(): void
    {
        [$cache, $logger] = $this->getMocks();

        $cache->expects($this->once())->method('get')->willReturn([]);
        $logger->expects($this->once())->method('emergency');

        $this->swap(Repository::class, $cache);
        $this->swap(LoggerInterface::class, $logger);

        /** @var GetTransferNews */
        $provider = app(GetTransferNews::class);

        $html = <<<"HTML"
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <title>Document</title>
            </head>
            <body>
                <article itemprop='itemListElement'>
                    <p>Queen Anifs Revenge</p>
                </article>
            </body>
            </html>
        HTML;

        $provider->toNewsArticles([
            $provider::ALIAS => Http::fake(fn () => Http::response($html))->get('https://google.com')
        ]);
    }

    public function test_will_return_stale_data_and_log_error_when_parent_attribute_changes(): void
    {
        [$cache, $logger] = $this->getMocks();

        $cache->expects($this->once())->method('get')->willReturn([]);
        $logger->expects($this->once())->method('emergency');

        $this->swap(Repository::class, $cache);
        $this->swap(LoggerInterface::class, $logger);

        /** @var GetTransferNews */
        $provider = app(GetTransferNews::class);

        $html = <<<"HTML"
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <title>Document</title>
            </head>
            <body>
                <article itemprop='changedAttribute'>
                </article>
            </body>
            </html>
        HTML;

        $provider->toNewsArticles([
            $provider::ALIAS => Http::fake(fn () => Http::response($html))->get('https://google.com')
        ]);
    }

    public function test_will_return_stale_data_and_log_error_when_http_request_is_not_successful(): void
    {
        [$cache, $logger] = $this->getMocks();

        $cache->expects($this->once())->method('get')->willReturn([]);
        $logger->expects($this->once())->method('emergency');

        $this->swap(Repository::class, $cache);
        $this->swap(LoggerInterface::class, $logger);

        /** @var GetTransferNews */
        $provider = app(GetTransferNews::class);

        $provider->toNewsArticles([
            $provider::ALIAS => Http::fake(fn () => Http::response(status: 404))->get('https://google.com')
        ]);
    }

    private function rawHtml()
    {
        return file_get_contents(base_path('Modules\Football\Tests\Stubs\Goal.com\transfer-news.html'));
    }
}
