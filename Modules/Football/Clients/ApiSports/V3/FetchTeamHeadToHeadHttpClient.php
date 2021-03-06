<?php

declare(strict_types=1);

namespace Module\Football\Clients\ApiSports\V3;

use Illuminate\Support\Collection;
use Module\Football\ValueObjects\TeamId;
use Module\Football\ValueObjects\TeamsHeadToHead;
use Module\Football\Collections\FixturesCollection;
use Module\Football\Contracts\Repositories\FetchTeamHeadToHeadRepositoryInterface;

final class FetchTeamHeadToHeadHttpClient extends ApiSportsClient implements FetchTeamHeadToHeadRepositoryInterface
{
    public function getHeadToHead(TeamId $teamOne, TeamId $teamTwo): TeamsHeadToHead
    {
        return $this->get('fixtures/headtohead', ['h2h' => "{$teamOne->toInt()}-{$teamTwo->toInt()}"])
            ->collect('response')
            ->map(new Response\FixtureResponseJsonMapper())
            ->pipe(fn (Collection $collection) => new TeamsHeadToHead($teamOne, $teamTwo, new FixturesCollection($collection->all())));
    }
}
