<?php

declare(strict_types=1);

namespace Module\Football\Clients\ApiSports\V3\Response;

use Module\Football\Clients\ApiSports\V3\CountryNameNormalizers\CountryNameNormalizerUsingSimilarText;
use Module\Football\DTO\Player;
use Module\Football\DTO\Builders\PlayerBuilder;

final class PlayerResponseJsonMapper extends Response
{
    private PlayerBuilder $builder;

    /**
     * @param array<string, mixed> $data
     * @param array<string, int> $playerPositionMap
     */
    public function __construct(
        array $data,
        private array $playerPositionMap = [],
        PlayerBuilder $playerBuilder = null
    ) {
        parent::__construct($data);

        $this->builder = $playerBuilder ?: new PlayerBuilder();
    }

    public function toDataTransferObject(): Player
    {
        return $this->builder
            ->setName($this->get('name'))
            ->setId($this->get('id'))
            ->when($this->has('position'), fn (PlayerBuilder $b) => $b->setPosition($this->playerPositionMap[$this->get('position')]))
            ->when($this->has('number'), fn (PlayerBuilder $b) => $b->setNumberOnShirt($this->get('number')))
            ->when($this->has('photo'), fn (PlayerBuilder $b) => $b->setPhotoUrl($this->get('photo')))
            ->when($this->has('nationality'), fn (PlayerBuilder $b) => $b->setNationality(new CountryNameNormalizerUsingSimilarText($this->get('nationality'))))
            ->when($this->has('height'), fn (PlayerBuilder $b) => $b->setHeight(floatval($this->get('height'))))
            ->build();
    }
}
