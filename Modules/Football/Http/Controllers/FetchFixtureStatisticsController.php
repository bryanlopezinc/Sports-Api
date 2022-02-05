<?php

declare(strict_types=1);

namespace Module\Football\Http\Controllers;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Module\Football\FixtureStatistic\FixtureStatistics;
use Module\Football\Http\Requests\FetchFixtureStatisticsRequest as Request;
use Module\Football\ValueObjects\FixtureId;
use Module\Football\Services\FetchFixtureStatisticsService;
use Module\Football\ValueObjects\TeamId;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Module\Football\DTO\FixtureStatistics as Statistics;
use Module\Football\Http\Resources\FixtureStatisticsResource;
use Module\Football\Http\Resources\PartialFixtureStaticsResource;

final class FetchFixtureStatisticsController
{
    public function __invoke(Request $request, FetchFixtureStatisticsService $service): PartialFixtureStaticsResource|JsonResource
    {
        $statistics = $this->getFixtureStatisticsForSelectedTeam($request, $service->fetch(FixtureId::fromRequest($request)));

        if (empty($statistics)) {
            return new FixtureStatisticsResource([]);
        }

        return new PartialFixtureStaticsResource($statistics);
    }

    /**
     * Get the fixture statistics for the two teams in a fixture
     * or only one team if a specific team was requested
     *
     * @throws HttpException
     * @return array<Statistics>
     */
    private function getFixtureStatisticsForSelectedTeam(Request $request, FixtureStatistics $fixtureStatistics): array
    {
        if ($fixtureStatistics->isEmpty()) {
            return [];
        }

        if (!$request->has('team')) {
            return [$fixtureStatistics->teamOne(), $fixtureStatistics->teamTwo()];
        }

        $teamId = TeamId::fromRequest($request, 'team');

        if (!$fixtureStatistics->hasTeam($teamId)) {
            throw new HttpException(Response::HTTP_BAD_REQUEST, 'The requested team does not belong to any team in the fixture');
        }

        return [$fixtureStatistics->forTeam($teamId)];
    }
}
