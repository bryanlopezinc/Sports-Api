<?php

declare(strict_types=1);

namespace Module\Football\Http\Controllers;

use Module\Football\ValueObjects\FixtureId;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Module\Football\Http\Requests\FetchFixturePlayersStatisticsRequest;
use Module\Football\Services\FetchFixturePlayersStatisticsService;
use Module\Football\Http\Resources\FixturePlayerStatisticsResource;
use Module\Football\ValueObjects\TeamId;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class FetchFixturePlayersStatisticsController
{
    public function __invoke(FetchFixturePlayersStatisticsRequest $request, FetchFixturePlayersStatisticsService $service): AnonymousResourceCollection
    {
        $playersStatistics = $service->fetch(FixtureId::fromRequest($request));

        if ($request->input('team')) {
            $playersStatistics = $playersStatistics->forTeam(TeamId::fromRequest($request, 'team'));

            throw_if($playersStatistics->isEmpty(), new HttpException(Response::HTTP_BAD_REQUEST, 'The team does not belong to any team in fixture'));
        }

        return FixturePlayerStatisticsResource::collection($playersStatistics->toLaravelCollection());
    }
}
