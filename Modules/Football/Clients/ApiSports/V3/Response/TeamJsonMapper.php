<?php

declare(strict_types=1);

namespace Module\Football\Clients\ApiSports\V3\Response;

use Module\Football\DTO\Team;
use Module\Football\DTO\Builders\TeamBuilder;
use Module\Football\Clients\ApiSports\V3\CountryNameNormalizers\CountryNameNormalizerUsingSimilarText;

final class TeamJsonMapper extends Response
{
    private TeamBuilder $builder;

    /**
     * @param array<string, mixed> $data
     */
    public function __construct(array $data, TeamBuilder $teamBuilder = null)
    {
        parent::__construct($data);

        $this->builder = $teamBuilder ?: new TeamBuilder();
    }

    public function toDataTransferObject(): Team
    {
        $this->setHasYearFounded();

        return $this->builder
            ->setId($this->get('id'))
            ->when($this->has('name'), fn (TeamBuilder $b) => $b->setName($this->get('name')))
            ->when($this->has('national'), fn (TeamBuilder $b) => $b->setIsNational($this->get('national')))
            ->when($this->has('logo'), fn (TeamBuilder $b) => $b->setLogoUrl($this->get('logo')))
            ->when($this->has('country'), fn (TeamBuilder $b) => $b->setCountry(new CountryNameNormalizerUsingSimilarText($this->get('country'))))
            ->build();
    }

    private function setHasYearFounded(): void
    {
        if (!$this->has('founded')) {
            return;
        }

        $this->builder->setHasYearFounded($hasYearInfo = $this->get('founded') !== null);

        if ($hasYearInfo) {
            $this->builder->setYearFounded($this->get('founded'));
        }
    }
}
