<?php

declare(strict_types=1);

namespace Module\Football\Clients\ApiSports\V3\Response;

use Module\Football\DTO\Builders\PlayerBuilder;
use Module\Football\ValueObjects\LeagueTopAssist;

final class LeagueTopAssistJsonMapper
{
    private Response $response;

    /**
     * @param array<string, mixed> $data
     */
    public function __construct(array $data, private ?PlayerBuilder $builder = null)
    {
        $this->response = new Response($data);
    }

    public function mapIntoLeagueTopAssist(): LeagueTopAssist
    {
        return new LeagueTopAssist(
            (new PlayerResponseJsonMapper($this->response->get('player'), [], $this->builder))->toDataTransferObject(),
            $this->response->get('statistics.0.goals.assists')
        );
    }
}
