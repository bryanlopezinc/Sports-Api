<?php

declare(strict_types=1);

namespace Module\Football\Clients\ApiSports\V3;

use Module\Football\DTO\Team;
use Illuminate\Support\Collection;
use Illuminate\Http\Client\Response;
use Module\Football\ValueObjects\TeamId;
use Module\Football\Collections\TeamsCollection;
use Module\Football\Collections\TeamIdsCollection;
use Module\Football\Contracts\Repositories\FetchTeamRepositoryInterface;
use Module\Football\Clients\ApiSports\V3\Response\TeamResponseJsonMapper;

final class FetchTeamHttpClient extends ApiSportsClient implements FetchTeamRepositoryInterface
{
    public function findTeamById(TeamId $id): Team
    {
        return $this->findManyById($id->toCollection())->sole();
    }

    public function findManyById(TeamIdsCollection $ids): TeamsCollection
    {
        $requests = $ids->toLaravelCollection()->map(fn (TeamId $id) => ApiSportsRequest::findTeamRequest($id))->all();

        return collect($this->pool($requests))
            ->map(fn (Response $response): array => $response->json('response.0'))
            ->map(new TeamResponseJsonMapper)
            ->pipe(fn (Collection $collection): TeamsCollection => new TeamsCollection($collection->all()));
    }
}
