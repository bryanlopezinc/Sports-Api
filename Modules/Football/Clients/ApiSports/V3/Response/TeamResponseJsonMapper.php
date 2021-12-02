<?php

declare(strict_types=1);

namespace Module\Football\Clients\ApiSports\V3\Response;

use Module\Football\DTO\Team;
use Module\Football\DTO\Builders\TeamBuilder;
use Module\Football\DTO\Builders\VenueBuilder;

final class TeamResponseJsonMapper
{
    private TeamBuilder $builder;
    private VenueBuilder $venueBuilder;
    private Response $response;

    /**
     * @param array<string, mixed> $response
     */
    public function __construct(
        array $response,
        TeamBuilder $teamBuilder = null,
        VenueBuilder $venueBuilder = null
    ) {
        $this->response = new Response($response);
        $this->builder = $teamBuilder ?: new TeamBuilder();
        $this->venueBuilder = $venueBuilder ?: new VenueBuilder();
    }

    public function toDataTransferObject(): Team
    {
        return $this->builder
            ->fromTeam((new TeamJsonMapper($this->response->get('team'), $this->builder))->toDataTransferObject())
            ->setVenue(
                $this->venueBuilder
                    ->setName($this->response->get('venue.name'))
                    ->setCity($this->response->get('venue.city'))
                    ->build()
            )->build();
    }
}
