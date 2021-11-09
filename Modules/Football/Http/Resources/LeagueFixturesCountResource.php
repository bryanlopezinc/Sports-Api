<?php

declare(strict_types=1);

namespace Module\Football\Http\Resources;

use App\ValueObjects\Date;
use Illuminate\Http\Request;
use Module\Football\LeagueFixturesGroup;
use Illuminate\Http\Resources\Json\JsonResource;
use Module\Football\Routes\FetchLeagueFixturesByDateRoute;

final class LeagueFixturesCountResource extends JsonResource
{
    public function __construct(private LeagueFixturesGroup $leagueFixturesCount)
    {
        parent::__construct($leagueFixturesCount);
    }

    /**
     * @param Request $request
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'league'       => new LeagueResource($this->leagueFixturesCount->league()),
            'fixtures'     => $this->leagueFixturesCount->fixturesCount(),
            'links'        => [
                'fixtures'     => new FetchLeagueFixturesByDateRoute(
                    $this->leagueFixturesCount->league()->getId(),
                    $this->leagueFixturesCount->league()->getSeason()->getSeason(),
                    new Date($request->get('date'))
                )
            ]
        ];
    }
}
