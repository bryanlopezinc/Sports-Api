<?php

declare(strict_types=1);

namespace Module\Football\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Module\Football\ValueObjects\CoachCareer;

final class CoachCareerResource extends JsonResource
{
    public function __construct(private CoachCareer $career)
    {
        parent::__construct($career);
    }

    /**
     * @param Request $request
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'type'       => 'football_coach_career',
            'attributes' => [
                'has_only_team_name'    => $this->career->onlyTeamManagedNameIsAvailable(),
                'team'                  => $this->transformTeamManaged(),
                'from'                  => $this->career->startedManagementOn()->toCarbon()->toDateString(),
                'is_current_team'       => $isCurrentTeam = $this->career->isTeamCurrentManger(),
                'to'                    => $this->when(!$isCurrentTeam, fn () => $this->career->leftTeamOn()->toCarbon()->toDateString()),
            ],
        ];
    }

    private function transformTeamManaged(): string|TeamResource
    {
        if ($this->career->onlyTeamManagedNameIsAvailable()) {
            return $this->career->teamManaged()->value();
        }

        return new TeamResource($this->career->teamManaged());
    }
}
