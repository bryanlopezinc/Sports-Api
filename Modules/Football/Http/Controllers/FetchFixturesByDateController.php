<?php

declare(strict_types=1);

namespace Module\Football\Http\Controllers;

use App\ValueObjects\Date;
use Module\Football\Services\FetchFixturesByDateService;
use Module\Football\Http\Requests\FetchFixturesByDateRequest;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Module\Football\Http\Resources\LeagueFixturesCountResource;

final class FetchFixturesByDateController
{
    public function __invoke(FetchFixturesByDateRequest $request, FetchFixturesByDateService $service): AnonymousResourceCollection
    {
        return LeagueFixturesCountResource::collection(
            $service->date(new Date($request->get('date')))
        );
    }
}
