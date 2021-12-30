<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

final class HandleDbTransactionsMiddleware
{
    /**
     * @param  \Illuminate\Http\Request $request
     * @param  \callable  $next
     * @return \Illuminate\Http\Response
     */
    public function handle($request, $next)
    {
        if (notInArray($request->method(), [
            Request::METHOD_PATCH,
            Request::METHOD_DELETE,
            Request::METHOD_PUT,
            Request::METHOD_POST,
        ])) {
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
}
