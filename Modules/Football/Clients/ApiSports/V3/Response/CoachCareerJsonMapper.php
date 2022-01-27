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
    private Response $response;

    /**
     * @param array<string, mixed> $response
     */
    public function __construct(array $response, private ?TeamBuilder $teamBuilder = null)
    {
        $this->response = new Response($response);
    }

    public function mapIntoCoachCareerObject(): CoachCareer
    {
        return new CoachCareer(
            $this->getTeam(),
            new Date($this->response->get('start')),
            $this->getEndDate()
        );
    }

    private function getEndDate(): ?Date
    {
        if ($this->response->get('end') === null) {
            return null;
        }

        return new Date($this->response->get('end'));
    }

    private function getTeam(): Team|TeamName
    {
        if ($this->response->get('team.id') === null) {
            return new TeamName($this->response->get('team.name'));
        }

        return (new TeamJsonMapper($this->response->get('team'), $this->teamBuilder))->toDataTransferObject();
    }
}
