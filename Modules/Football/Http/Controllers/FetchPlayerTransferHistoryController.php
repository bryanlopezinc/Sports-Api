<?php

declare(strict_types=1);

namespace Module\Football\Http\Controllers;

use Illuminate\Http\Request;
use App\Rules\ResourceIdRule;
use Module\Football\ValueObjects\PlayerId;
use Module\Football\Http\Resources\PlayerTransferHistoryResource;
use Module\Football\Contracts\Repositories\FetchPlayerTransferHistoryRepositoryInterface as Repository;

final class FetchPlayerTransferHistoryController
{
    public function __invoke(Request $request, Repository $repository): PlayerTransferHistoryResource
    {
        $request->validate(['id' => ['required', new ResourceIdRule]]);

        return new PlayerTransferHistoryResource($repository->forPlayer(new PlayerId($request->input('id'))));
    }
}
