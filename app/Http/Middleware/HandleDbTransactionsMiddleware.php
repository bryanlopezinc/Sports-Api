<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

final class HandleDbTransactionsMiddleware
{
    public const METHODS = [
        Request::METHOD_PATCH,
        Request::METHOD_DELETE,
        Request::METHOD_PUT,
        Request::METHOD_POST,
    ];

    /**
     * @param  \Illuminate\Http\Request $request
     * @param  \callable  $next
     * @return \Illuminate\Http\Response
     */
    public function handle($request, $next)
    {
        if (!$this->shouldApplyMiddlewareOn($request)) {
            return $next($request);
        }

        DB::beginTransaction();

        /** @var \Illuminate\Http\Response */
        $response = $next($request);

        if ($response->isServerError() || $response->isClientError()) {
            DB::rollBack();
        } else {
            DB::commit();
        }

        return $response;
    }

    private function shouldApplyMiddlewareOn(Request $request): bool
    {
        return inArray($request->method(), self::METHODS);
    }
}
