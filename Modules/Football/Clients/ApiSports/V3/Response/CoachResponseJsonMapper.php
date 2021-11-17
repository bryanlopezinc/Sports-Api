<?php

declare(strict_types=1);

namespace Module\Football\Clients\ApiSports\V3\Response;

use Module\Football\DTO\Coach;
use Module\Football\DTO\Builders\TeamBuilder;
use Module\Football\DTO\Builders\CoachBuilder;
use Module\Football\Clients\ApiSports\V3\CountryNameNormalizers\CountryNameNormalizerUsingSimilarText;

final class CoachResponseJsonMapper extends Response
{
    private CoachBuilder $builder;
    private TeamBuilder $teamBuilder;

    /**
     * @param array<string, mixed> $response
     */
    public function __construct(array $response, CoachBuilder $builder = null, TeamBuilder $teamBuilder = null)
    {
        parent::__construct($response);

        $this->builder = $builder ?: new CoachBuilder();
        $this->teamBuilder = $teamBuilder ?: new TeamBuilder();
    }

    public function mapIntoDataTransferObject(): Coach
    {
        $this->setTeam();

        return $this->builder
            ->id($this->get('id'))
            ->name($this->get('firstname') . ' ' . $this->get('lastname'))
            ->dateOfBirth($this->get('birth.date'))
            ->photoUrl($this->get('photo'))
            ->setCountry(new CountryNameNormalizerUsingSimilarText($this->get('nationality')))
            ->build();
    }

    private function setTeam(): void
    {
        if ($this->get('team') === null) {
            $this->builder->team(null);

            return;
        }

        $this->builder->team(
            (new TeamJsonMapper($this->get('team'), $this->teamBuilder))->toDataTransferObject()
        );
    }
}
