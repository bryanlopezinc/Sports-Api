<?php

declare(strict_types=1);

namespace Module\Football\Clients\ApiSports\V3\Response;

use Module\Football\DTO\Team;
use Module\Football\DTO\Builders\TeamBuilder;
use Module\Football\Clients\ApiSports\V3\CountryNameNormalizers\CountryNameNormalizerUsingSimilarText;

final class TeamJsonMapper
{
    private TeamBuilder $builder;
    private Response $response;

    /**
     * @param array<string, mixed> $data
     */
    public function __construct(array $data, TeamBuilder $teamBuilder = null)
    {
        $this->response = new Response($data);
        $this->builder = $teamBuilder ?: new TeamBuilder();
    }

    public function toDataTransferObject(): Team
    {
        $this->setHasYearFounded();

        return $this->builder
            ->setId($this->response->get('id'))
            ->when($this->response->has('name'), fn (TeamBuilder $b) => $b->setName($this->response->get('name')))
            ->when($this->response->has('national'), fn (TeamBuilder $b) => $b->setIsNational($this->response->get('national')))
            ->when($this->response->has('logo'), fn (TeamBuilder $b) => $b->setLogoUrl($this->response->get('id')))
            ->when($this->response->has('country'), fn (TeamBuilder $b) => $b->setCountry(new CountryNameNormalizerUsingSimilarText($this->response->get('country'))))
            ->build();
    }

    private function setHasYearFounded(): void
    {
        if (!$this->response->has('founded')) {
            return;
        }

        $this->builder->setHasYearFounded($hasYearInfo = $this->response->get('founded') !== null);

        if ($hasYearInfo) {
            $this->builder->setYearFounded($this->response->get('founded'));
        }
    }
}
