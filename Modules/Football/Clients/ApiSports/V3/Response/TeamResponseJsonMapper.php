<?php

declare(strict_types=1);

namespace Module\Football\Clients\ApiSports\V3\Response;

use Module\Football\DTO\Team;
use Module\Football\DTO\Builders\TeamBuilder;
use Module\Football\DTO\Builders\VenueBuilder;

final class TeamResponseJsonMapper extends Response
{
    private TeamBuilder $builder;
    private VenueBuilder $venueBuilder;

    /**
     * @param array<string, mixed> $response
     */
    public function __construct(
        array $response,
        TeamBuilder $teamBuilder = null,
        VenueBuilder $venueBuilder = null
    ) {
        parent::__construct($response);

        $this->builder = $teamBuilder ?: new TeamBuilder();
        $this->venueBuilder = $venueBuilder ?: new VenueBuilder();
    }

    public function toDataTransferObject(): Team
    {
        return $this->builder
            ->fromTeam((new TeamJsonMapper($this->get('team'), $this->builder))->toDataTransferObject())
            ->setVenue(
                $this->venueBuilder
                    ->setName($this->get('venue.name'))
                    ->setCity($this->get('venue.city'))
                    ->build()
            )->build();
    }
}
