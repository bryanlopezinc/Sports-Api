<?php

declare(strict_types=1);

namespace Module\User\Predictions\Football;

use Illuminate\Http\Request;
use App\Exceptions\Http\HttpException;
use Module\Football\ValueObjects\FixtureId;
use Symfony\Component\HttpFoundation\Response;
use Module\User\Predictions\Football\Contracts\FetchFixturePredictionsRepositoryInterface;
use Module\User\ValueObjects\UserId;

final class EnsureUserCanPredictFixtureMiddleware
{
    public function __construct(private FetchFixturePredictionsRepositoryInterface $repository)
    {
    }

    /**
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return \Illuminate\Http\Response
     */
    public function handle(Request $request, $next)
    {
        //Ensure all attributes needed for validation are present and valid.
        app(PredictFixtureRequest::class);

        if ($this->repository->userHasPredictedFixture(UserId::fromAuthUser(), FixtureId::fromRequest($request, 'fixture_id'))) {
            throw new HttpException(Response::HTTP_CONFLICT, 'User can only predict a fixture once');
        }

        return $next($request);
    }
}
