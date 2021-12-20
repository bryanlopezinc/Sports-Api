<?php

declare(strict_types=1);

namespace Tests\Unit\HashId;

use Tests\TestCase;
use App\HashId\ConvertHashedValuesToIntegerMiddleware;
use App\Exceptions\Http\ResourceNotFoundHttpException;

class ConvertHashIdToIntegerMiddlewareTest extends TestCase
{
    public function test_will_convert_hashed_keys_to_integer(): void
    {
        request()->merge([
            'foo'   => $this->hashId(200),
            'bar'   => $this->hashId(150),
            'baz'   => $this->hashId(400)
        ]);

        /** @var ConvertHashedValuesToIntegerMiddleware */
        $middleware = app(ConvertHashedValuesToIntegerMiddleware::class);

        $middleware->handle(request(), function () {
        }, 'foo', 'bar', 'baz');

        $this->assertEquals(200, request()->input('foo'));
        $this->assertEquals(150, request()->input('bar'));
        $this->assertEquals(400, request()->input('baz'));
    }

    public function test_throws_not_found_exception_when_ids_are_invalid(): void
    {
        $this->expectException(ResourceNotFoundHttpException::class);

        request()->merge(['foo' => 'foobar']);

        /** @var ConvertHashedValuesToIntegerMiddleware */
        $middleware = app(ConvertHashedValuesToIntegerMiddleware::class);

        $middleware->handle(request(), function () {
        }, 'foo');
    }
}
