<?php

declare(strict_types=1);

namespace Module\Football\Clients\ApiSports\V3\Response;

use Module\Football\Clients\ApiSports\V3\CountryNameNormalizers\CountryNameNormalizerUsingSimilarText;
use Module\Football\DTO\Player;
use Module\Football\DTO\Builders\PlayerBuilder;

final class PlayerResponseJsonMapper
{
    private PlayerBuilder $builder;
    private Response $response;

    /**
     * @param array<string, mixed> $data
     * @param array<string, int> $playerPositionMap
     */
    public function __construct(
        array $data,
        private array $playerPositionMap = [],
        PlayerBuilder $playerBuilder = null
    ) {
        $this->response = new Response($data);
        $this->builder = $playerBuilder ?: new PlayerBuilder();
    }

    public function toDataTransferObject(): Player
    {
        return $this->builder
            ->setName($this->response->get('name'))
            ->setId($this->response->get('id'))
            ->when($this->response->has('position'), fn (PlayerBuilder $b) => $b->setPosition($this->playerPositionMap[$this->response->get('position')]))
            ->when($this->response->has('number'), fn (PlayerBuilder $b) => $b->setNumberOnShirt($this->response->get('number')))
            ->when($this->response->has('photo'), fn (PlayerBuilder $b) => $b->setPhotoUrl($this->response->get('photo')))
            ->when($this->response->has('nationality'), fn (PlayerBuilder $b) => $b->setNationality(new CountryNameNormalizerUsingSimilarText($this->response->get('nationality'))))
            ->when($this->response->has('height'), fn (PlayerBuilder $b) => $b->setHeight(floatval($this->response->get('height'))))
            ->build();
    }
}
