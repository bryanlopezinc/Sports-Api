<?php

declare(strict_types=1);

namespace Module\Football\Clients\ApiSports\V3\Response;

use Module\Football\DTO\Player;
use Module\Football\DTO\Builders\PlayerBuilder;
use Module\Football\ValueObjects\PlayerPosition;

final class TeamSquadJsonResponseMapper extends Response
{
    private const PLAYER_POSITION_MAP = [
        'Goalkeeper'  => PlayerPosition::GOALIE,
        'Defender'    => PlayerPosition::DEFENDER,
        'Midfielder'  => PlayerPosition::MIDFIELDER,
        'Attacker'    => PlayerPosition::ATTACKER
    ];

    /**
     * @param array<string, mixed> $data
     */
    public function __construct(array $data, private ?PlayerBuilder $builder = null)
    {
        parent::__construct($data);
    }

    public function tooDataTransferObject(): Player
    {
        return (new PlayerResponseJsonMapper($this->data, self::PLAYER_POSITION_MAP, $this->builder))->toDataTransferObject();
    }
}
