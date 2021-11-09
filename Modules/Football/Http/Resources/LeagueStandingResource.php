<?php

declare(strict_types=1);

namespace Module\Football\Http\Resources;

use Illuminate\Http\Request;
use Module\Football\DTO\StandingData;
use Module\Football\DTO\LeagueStanding;
use Module\Football\ValueObjects\TeamForm;
use Module\Football\Collections\LeagueTable;
use Illuminate\Http\Resources\Json\JsonResource;

final class LeagueStandingResource extends JsonResource
{
    public function __construct(private LeagueTable $leagueTable)
    {
        parent::__construct($leagueTable);
    }

    /**
     * @param Request $request
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'league'      => new LeagueResource($this->leagueTable->getLeague()),
            'standings'   => $this->leagueTable->toLaravelCollection()->map(fn (LeagueStanding $standing): array => [
                'points'            => $standing->getPoints(),
                'position'          => $standing->getRank(),
                'team'              => new TeamResource($standing->getTeam()),
                'team_form'         => $this->transformTeamForm($standing->getTeamCurrentForm()),
                'played'            => $standing->getStandingRecord()->getPlayed(),
                'won'               => $standing->getStandingRecord()->getTotalWins(),
                'lost'              => $standing->getStandingRecord()->getTotalLoses(),
                'draws'             => $standing->getStandingRecord()->getTotalDraws(),
                'home_record'       => $this->merge($this->transformStandingData($standing->getStandingHomeRecord())),
                'away_record'       => $this->merge($this->transformStandingData($standing->getStandingAwayRecord())),
                'goal_difference'   => $standing->getGoalsDifference(),
                'goals_found'       => $standing->getStandingRecord()->getTotalGoalsScored(),
                'goals_against'     => $standing->getStandingRecord()->getTotalGoalsConceeded(),
            ])->all()
        ];
    }

    /**
     * @return array<string, int>
     */
    private function transformStandingData(StandingData $standingData): array
    {
        return [
            'played'         => $standingData->getPlayed(),
            'win'            => $standingData->getTotalWins(),
            'lose'           => $standingData->getTotalLoses(),
            'draw'           => $standingData->getTotalDraws(),
            'goals_found'    => $standingData->getTotalGoalsScored(),
            'goals_against'  => $standingData->getTotalGoalsConceeded(),
        ];
    }

    private function transformTeamForm(TeamForm $form): string
    {
        return collect($form->toArray())
            ->map(fn (string $form): string => match ($form) {
                TeamForm::WIN   => 'W',
                TeamForm::LOOSE => 'L',
                TeamForm::DRAW  => 'D',
            })
            ->implode('');
    }
}
