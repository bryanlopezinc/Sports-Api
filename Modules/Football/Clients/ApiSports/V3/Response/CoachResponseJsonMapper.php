<?php

declare(strict_types=1);

namespace Module\Football\Clients\ApiSports\V3\Response;

use Module\Football\DTO\Coach;
use Module\Football\DTO\Builders\TeamBuilder;
use Module\Football\DTO\Builders\CoachBuilder;
use Module\Football\Clients\ApiSports\V3\CountryNameNormalizers\CountryNameNormalizerUsingSimilarText;

final class CoachResponseJsonMapper
{
    public function __construct(
        private readonly array $response,
        private CoachBuilder $builder = new CoachBuilder(),
        private TeamBuilder $teamBuilder = new TeamBuilder()
    ) {
    }

    public function mapIntoDataTransferObject(): Coach
    {
        $this->setTeam();

        return $this->builder
            ->id($this->response['id'])
            ->name($this->response['firstname'] . ' ' . $this->response['lastname'])
            ->dateOfBirth($this->response['birth']['date'])
            ->photoUrl($this->response['id'])
            ->setCountry(new CountryNameNormalizerUsingSimilarText($this->response['nationality']))
            ->build();
    }

    private function setTeam(): void
    {
        if ($this->response['team'] === null) {
            $this->builder->team(null);

            return;
        }

        $this->builder->team(
            (new TeamJsonMapper($this->response['team'], $this->teamBuilder))->toDataTransferObject()
        );
    }
}
