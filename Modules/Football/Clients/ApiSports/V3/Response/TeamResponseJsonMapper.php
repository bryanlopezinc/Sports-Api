<?php

declare(strict_types=1);

namespace Module\Football\Clients\ApiSports\V3\Response;

use Module\Football\DTO\Team;
use Module\Football\DTO\Builders\TeamBuilder;
use Module\Football\Venue;
use App\ValueObjects\NonEmptyString as VenueName;

final class TeamResponseJsonMapper
{
    public function __construct(private TeamBuilder $builder = new TeamBuilder)
    {
    }

    public function __invoke(array $data): Team
    {
        return $this->builder
            ->fromTeam((new TeamJsonMapper($data['team'], $this->builder))->toDataTransferObject())
            ->setVenue(new Venue(new VenueName($data['venue']['name']), $data['venue']['city']))->build();
    }
}
