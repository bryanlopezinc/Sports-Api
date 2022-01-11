<?php

declare(strict_types=1);

namespace Module\Football\Http\Controllers;

use App\Utils\Config;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Module\Football\Contracts\Repositories\FetchLiveFixturesRepositoryInterface;
use Module\Football\Http\Resources\PartialFixtureResource;
use Module\Football\Rules\PartialFixtureFieldsRule;

final class FetchLiveFixturesController
{
    public function __invoke(FetchLiveFixturesRepositoryInterface $client, Request $request): JsonResponse
    {
        $request->validate([
            'filter' => ['sometimes', 'filled', new PartialFixtureFieldsRule]
        ]);

        $resource = PartialFixtureResource::collection($client->FetchLiveFixtures()->toLaravelCollection());

        $resource = tap($resource, function (AnonymousResourceCollection $value): void {
            $value->collection = $value->collection->map(function (PartialFixtureResource $resource) {
                return $resource->setFilterInputName('filter');
            });
        });

        return response()
            ->json($resource)
            ->header('max-age', Config::get('football.fetchLiveFixturesResponseMaxAge'));
    }
}
