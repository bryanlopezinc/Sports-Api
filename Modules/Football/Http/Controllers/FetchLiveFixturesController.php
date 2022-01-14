<?php

declare(strict_types=1);

namespace Module\Football\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Module\Football\Contracts\Repositories\FetchLiveFixturesRepositoryInterface;
use Module\Football\Http\Resources\FixtureResource;
use Module\Football\Http\Resources\PartialFixtureResource;
use Module\Football\Rules\FixtureFieldsRule;

final class FetchLiveFixturesController
{
    public function __invoke(FetchLiveFixturesRepositoryInterface $client, Request $request): AnonymousResourceCollection
    {
        $request->validate([
            'filter' => ['sometimes', 'filled', new FixtureFieldsRule]
        ]);

        $resource = PartialFixtureResource::collection(FixtureResource::collection($client->FetchLiveFixtures()->toLaravelCollection()));

        return tap($resource, function (AnonymousResourceCollection $value): void {
            $value->collection = $value->collection->map(function (PartialFixtureResource $resource) {
                return $resource->setFilterInputName('filter');
            });
        });
    }
}
