<?php

declare(strict_types=1);

namespace Tests\Unit\HashId;

use Tests\TestCase;
use App\HashId\ConvertHashedValuesToIntegerMiddleware;
use App\Exceptions\Http\ResourceNotFoundHttpException;

class ConvertHashIdToIntegerMiddlewareTest extends TestCase
{
    private ConvertHashedValuesToIntegerMiddleware $middleware;

    protected function setUp(): void
    {
        parent::setUp();

        $this->middleware = app(ConvertHashedValuesToIntegerMiddleware::class);
    }

    public function test_will_convert_hashed_keys_to_integer(): void
    {
        request()->merge([
            'foo'   => $this->hashId(200),
            'bar'   => $this->hashId(150),
            'baz'   => $this->hashId(400)
        ]);

        $this->middleware->handle(request(), function () {
        }, 'foo', 'bar', 'baz');

        $this->assertEquals(200, request()->input('foo'));
        $this->assertEquals(150, request()->input('bar'));
        $this->assertEquals(400, request()->input('baz'));
    }

    public function test_will_convert_all_hashed_values_in_array(): void
    {
        request()->merge([
            'foo' => [$this->hashId(200), $this->hashId(150), $this->hashId(400)],
        ]);

        $this->middleware->handle(request(), function () {
        }, 'foo', 'bar', 'baz');

        $this->assertEquals([200, 150, 400], request()->input('foo'));
    }

    public function test_will_throw_exception_when_array_contains_invalid_item(): void
    {
        $this->expectExceptionMessage('The foo.2 must be a string.');

        request()->merge([
            'foo' => [$this->hashId(200), $this->hashId(350), 99],
        ]);

        $this->middleware->handle(request(), function () {
        }, 'foo');
    }

    public function test_throws_not_found_exception_when_ids_are_invalid(): void
    {
        $this->expectException(ResourceNotFoundHttpException::class);

        request()->merge(['foo' => 'foobar']);

        $this->middleware->handle(request(), function () {
        }, 'foo');
    }

    public function test_id_must_be_a_string(): void
    {
        $this->expectExceptionMessage('The foo must be a string.');

        $request = request()->merge(['foo' => 22]);

        $this->middleware->handle($request, function () {
        }, 'foo');
    }
}
