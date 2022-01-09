<?php

declare(strict_types=1);

namespace Module\Football\Http\Resources;

use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Module\Football\Collections\LeagueTable;
use Illuminate\Http\Resources\Json\JsonResource;
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
        $original = (new LeagueStandingResource($this->leagueTable))->toArray($request);
        $partialResponseRequest = PartialLeagueStandingRequest::fromRequest($request, $this->filterInputName);

        if ($partialResponseRequest->isEmpty()) {
            return $original;
        }

        $original['standings'] = array_map(fn (array $standing) => Arr::only($standing, $partialResponseRequest->all()), $original['standings']);

        if (!$partialResponseRequest->wants('league')) {
            Arr::forget($original, 'league');
        } else {
            $original['league'] = $this->getLeagueResource();
        }

        return $original;
    }

    private function getLeagueResource(): JsonResource
    {
        if (!$this->usePartialLeagueResource) {
            return new LeagueResource($this->leagueTable->getLeague());
        }

        return new PartialLeagueResource($this->leagueTable->getLeague(), $this->leagueFilterInputName);
    }
}
