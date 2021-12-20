<?php

declare(strict_types=1);

namespace Module\Football\Clients\ApiSports\V3\Response;

use Module\Football\DTO\Coach;
use Module\Football\DTO\Builders\TeamBuilder;
use Module\Football\DTO\Builders\CoachBuilder;
use Module\Football\Clients\ApiSports\V3\CountryNameNormalizers\CountryNameNormalizerUsingSimilarText;

final class CoachResponseJsonMapper
{
    private CoachBuilder $builder;
    private TeamBuilder $teamBuilder;
    private Response $response;

    /**
     * @param array<string, mixed> $response
     */
    public function __construct(array $response, CoachBuilder $builder = null, TeamBuilder $teamBuilder = null)
    {
        $this->response = new Response($response);
        $this->builder = $builder ?: new CoachBuilder();
        $this->teamBuilder = $teamBuilder ?: new TeamBuilder();
    }

    public function mapIntoDataTransferObject(): Coach
    {
        $this->setTeam();

        return $this->builder
            ->id($this->response->get('id'))
            ->name($this->response->get('firstname') . ' ' . $this->response->get('lastname'))
            ->dateOfBirth($this->response->get('birth.date'))
            ->photoUrl($this->response->get('id'))
            ->setCountry(new CountryNameNormalizerUsingSimilarText($this->response->get('nationality')))
            ->build();
    }

    private function setTeam(): void
    {
        if ($this->response->get('team') === null) {
            $this->builder->team(null);

            return;
        }

        $this->builder->team(
            (new TeamJsonMapper($this->response->get('team'), $this->teamBuilder))->toDataTransferObject()
        );
    }
}
