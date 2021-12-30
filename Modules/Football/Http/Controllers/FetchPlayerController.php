<?php

declare(strict_types=1);

namespace Module\Football\Http\Controllers;

use Illuminate\Http\Request;
use App\Rules\ResourceIdRule;
use Module\Football\ValueObjects\PlayerId;
use Module\Football\Http\Resources\PlayerResource;
use Module\Football\Contracts\Repositories\FetchPlayerRepositoryInterface;

final class FetchPlayerController
{
    public function __invoke(Request $request, FetchPlayerRepositoryInterface $repository): PlayerResource
    {
        $request->validate(['id' => ['required', new ResourceIdRule]]);

        return new PlayerResource($repository->findById(new PlayerId($request->input('id'))));
    }
}
