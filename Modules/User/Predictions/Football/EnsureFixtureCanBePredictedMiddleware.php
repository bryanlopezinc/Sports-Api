<?php

declare(strict_types=1);

namespace Module\User\Predictions\Football;

use Illuminate\Http\Request;
use App\Exceptions\Http\HttpException;
use Module\Football\ValueObjects\FixtureId;
use Symfony\Component\HttpFoundation\Response;
use Module\Football\ValueObjects\FixtureStatus;
use Module\Football\Services\FetchFixtureService;

final class EnsureFixtureCanBePredictedMiddleware
{
    public function __construct(private FetchFixtureService $service)
    {
    }

    /**
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return \Illuminate\Http\Response
     */
    public function handle(Request $request, $next)
    {
        $status = $this->service->fetchFixture(FixtureId::fromRequest($request, 'fixture_id'))->status();

        if ($status->code() === FixtureStatus::NOT_STARTED) {
            return $next($request);
        }

        throw new HttpException(Response::HTTP_FORBIDDEN, 'Can only predict a fixture that is not started');
    }
}
