<?php

declare(strict_types=1);

namespace Module\Football\Clients\ApiSports\V3\Response;

use Module\Football\DTO\Team;
use Module\Football\DTO\Builders\TeamBuilder;
use Module\Football\Venue;
use Module\Football\ValueObjects\Name;

final class TeamResponseJsonMapper
{
    private TeamBuilder $builder;
    private Response $response;

    /**
     * @param array<string, mixed> $response
     */
    public function __construct(
        array $response,
        TeamBuilder $teamBuilder = null,
    ) {
        $this->response = new Response($response);
        $this->builder = $teamBuilder ?: new TeamBuilder();
    }

    public function toDataTransferObject(): Team
    {
        return $this->builder
            ->fromTeam((new TeamJsonMapper($this->response->get('team'), $this->builder))->toDataTransferObject())
            ->setVenue(new Venue(new Name($this->response->get('venue.name')), $this->response->get('venue.city'))
            )->build();
    }
}
