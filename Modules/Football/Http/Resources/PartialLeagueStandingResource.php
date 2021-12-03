<?php

declare(strict_types=1);

namespace Module\Football\Http\Resources;

use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Module\Football\ValueObjects\TeamId;
use Module\Football\Collections\LeagueTable;
use Illuminate\Http\Resources\Json\JsonResource;
use Module\Football\Collections\TeamIdsCollection;
use Module\Football\Http\PartialLeagueStandingRequest;

final class PartialLeagueStandingResource extends JsonResource
{
    /*** The input name key name to get standings filters from request.*/
    public string $filterInputName;
    public bool $usePartialLeagueResource = true;
    /*** The input name key name to get league filters from request.*/
    public string $leagueFilterInputName;

    public function __construct(private LeagueTable $leagueTable)
    {
        parent::__construct($leagueTable);
    }

    public function setFilterInputName(string $name): self
    {
        $this->filterInputName = $name;

        return $this;
    }

    public function setLeagueFilterInputName(string $name): self
    {
        $this->leagueFilterInputName = $name;

        return $this;
    }

    /**
     * @param Request $request
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        [$originalResponse, $partialResponse] = [(new LeagueStandingResource($this->leagueTable))->toArray($request), []];

        $partialResponseRequest = PartialLeagueStandingRequest::fromRequest($request, $this->filterInputName);

        if ($partialResponseRequest->wants('league') || !$partialResponseRequest->wantsPartialResponse()) {
            $partialResponse['league'] = $this->getLeagueResource();
        };

        $requestedTeams = $this->getOnlyRequestedTeamsFrom($request);

        $partialResponse['standings'] = collect($originalResponse['standings'])
            ->filter(fn (array $standing) => $requestedTeams->has($standing['team']->resource->getId()))
            ->map(fn (array $standing) => $partialResponseRequest->wantsPartialResponse() ? Arr::only($standing, $partialResponseRequest->all()) : $standing)
            ->all();

        return $partialResponse;
    }

    private function getLeagueResource(): JsonResource
    {
        if (!$this->usePartialLeagueResource) {
            return new LeagueResource($this->leagueTable->getLeague());
        }

        return new PartialLeagueResource($this->leagueTable->getLeague(), $this->leagueFilterInputName);
    }

    /**
     * Throws exception if a requested team id does not exists in league table
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    private function getOnlyRequestedTeamsFrom(Request $request): TeamIdsCollection
    {
        if (!$request->filled('teams')) {
            return $this->leagueTable->teams()->pluckIds();
        }

        $teamIdsInTable = $this->leagueTable->teams()->pluckIds();

        return collect(explode(',', $request->input('teams')))
            ->map(fn ($id) => new TeamId((int)$id))
            ->each(function (TeamId $teamId) use ($teamIdsInTable) {
                if (!$teamIdsInTable->has($teamId)) {
                    abort(400, sprintf('Team with id %s could not be found in league table', $teamId->toInt()));
                }
            })
            ->pipe(fn (Collection $collection) => new TeamIdsCollection($collection->all()));
    }
}
