<?php

declare(strict_types=1);

namespace Module\Football\Http\Middleware;

use App\Exceptions\Http\ResourceNotFoundHttpException;
use Illuminate\Http\Request;
use Module\Football\Cache\FixturesThatExistsCacheRepository;
use Module\Football\ValueObjects\FixtureId;

final class EnsureFixtureExistsMiddleware
{
    public function __construct(private FixturesThatExistsCacheRepository $repository)
    {
    }

    /**
     * @param  \Closure $next
     * @return \Illuminate\Http\Response
     *
     *@throws ResourceNotFoundHttpException
     */
    public function handle(Request $request, $next, string $requestKey)
    {
        if (!$request->filled($requestKey)) {
            return $next($request);
        }

        $fixtureExists = $this->repository->exists(FixtureId::fromRequest($request, $requestKey));

        if (!$fixtureExists) {
            throw new ResourceNotFoundHttpException;
        }

        return $next($request);
    }

    public static function key(string $key): string
    {
        return 'ensureValidFixture:' . $key;
    }
}
