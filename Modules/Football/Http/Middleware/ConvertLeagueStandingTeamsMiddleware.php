<?php

declare(strict_types=1);

namespace Module\Football\Http\Middleware;

use Illuminate\Validation\ValidationException;
use App\Exceptions\Http\ResourceNotFoundHttpException;
use App\HashId\ConvertHashedValuesToIntegerMiddleware;
use Illuminate\Http\Request;

/**
 * if custom teams where requested for a league standing request
 * Convert the hashed team ids back to original values
 */
final class ConvertLeagueStandingTeamsMiddleware
{
    public function __construct(private ConvertHashedValuesToIntegerMiddleware $middleware)
    {
    }

    /**
     * Handle an incoming request.
     *
     * @throws ValidationException
     */
    public function handle(Request $request, \Closure $next)
    {
        if (!$request->filled('teams')) {
            return $next($request);
        }

        $request->validate(['teams' => ['string']]);

        $callback = function (string $id, int $index): int {
            try {
                return $this->middleware->transform($id);
            } catch (ResourceNotFoundHttpException) {
                throw ValidationException::withMessages([
                    "teams.$index" => 'The given team id is invalid'
                ]);
            }
        };

        $request->merge([
            'teams' => collect(explode(',', $request->input('teams')))->map($callback)->implode(',')
        ]);

        return $next($request);
    }
}
