<?php

declare(strict_types=1);

namespace Module\Football\Clients\ApiSports\V3\Response;

use App\ValueObjects\Date;
use Module\Football\DTO\Team;
use Module\Football\DTO\Builders\TeamBuilder;
use Module\Football\ValueObjects\CoachCareer;
use Module\Football\ValueObjects\Name;

final class CoachCareerJsonMapper extends Response
{
    /**
     * @param array<string, mixed> $response
     */
    public function __construct(array $response, private ?TeamBuilder $teamBuilder = null)
    {
        parent::__construct($response);
    }

    public function mapIntoCoachCareerObject(): CoachCareer
    {
        return new CoachCareer(
            $this->getTeam(),
            new Date($this->get('start')),
            $this->getEndDate()
        );
    }

    private function getEndDate(): ?Date
    {
        if ($this->get('end') === null) {
            return null;
        }

        return new Date($this->get('end'));
    }

    private function getTeam(): Team|Name
    {
        if ($this->get('team.id') === null) {
            return new Name($this->get('team.name'));
        }

        return (new TeamJsonMapper($this->get('team'), $this->teamBuilder))->toDataTransferObject();
    }
}
