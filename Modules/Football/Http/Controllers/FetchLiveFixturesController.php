<?php

declare(strict_types=1);

namespace Module\Football\Http\Controllers;

use App\Utils\Config;
use Illuminate\Http\JsonResponse;
use Module\Football\Http\Resources\FixtureResource;
use Module\Football\Contracts\Repositories\FetchLiveFixturesRepositoryInterface;

final class FetchLiveFixturesController
{
    public function __invoke(FetchLiveFixturesRepositoryInterface $client): JsonResponse
    {
        return response()
            ->json(FixtureResource::collection($client->FetchLiveFixtures()->toLaravelCollection()))
            ->header('max-age', Config::get('football.fetchLiveFixturesResponseMaxAge'));
    }
}
