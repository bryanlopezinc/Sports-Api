<?php

declare(strict_types=1);

namespace Module\Football\Clients\ApiSports\V3\Response;

use App\ValueObjects\Date;
use Module\Football\DTO\Team;
use Module\Football\DTO\Builders\TeamBuilder;
use Module\Football\ValueObjects\CoachCareer;
use App\ValueObjects\NonEmptyString as TeamName;

final class CoachCareerJsonMapper
{
    public function __construct(private TeamBuilder $teamBuilder = new TeamBuilder)
    {
    }

    public function __invoke(array $response): CoachCareer
    {
        return new CoachCareer(
            $this->getTeam($response),
            new Date($response['start']),
            $this->getEndDate($response)
        );
    }

    private function getEndDate(array $response): ?Date
    {
        if ($response['end'] === null) {
            return null;
        }

        return new Date($response['end']);
    }

    private function getTeam(array $response): Team|TeamName
    {
        if ($response['team']['id'] === null) {
            return new TeamName($response['team']['name']);
        }

        return (new TeamJsonMapper($response['team'], $this->teamBuilder))->toDataTransferObject();
    }
}
