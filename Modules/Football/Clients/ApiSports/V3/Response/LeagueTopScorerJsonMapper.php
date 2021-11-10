<?php

declare(strict_types=1);

namespace Module\Football\Clients\ApiSports\V3\Response;

use Module\Football\DTO\Builders\PlayerBuilder;
use Module\Football\ValueObjects\LeagueTopScorer;

final class LeagueTopScorerJsonMapper extends Response
{
    /**
     * @param array<string, mixed> $data
     */
    public function __construct(array $data, private ?PlayerBuilder $builder = null)
    {
        parent::__construct($data);
    }

    public function mapIntoLeagueScorerObject(): LeagueTopScorer
    {
        return new LeagueTopScorer(
            (new PlayerResponseJsonMapper($this->get('player'), [], $this->builder))->toDataTransferObject(),
            $this->get('statistics.0.goals.total')
        );
    }
}
