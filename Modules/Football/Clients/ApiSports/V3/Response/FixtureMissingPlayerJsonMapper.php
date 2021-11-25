<?php

declare(strict_types=1);

namespace Module\Football\Clients\ApiSports\V3\Response;

use Module\Football\TeamMissingPlayer;
use Module\Football\DTO\Builders\PlayerBuilder;
use Module\Football\ValueObjects\ReasonForMissingFixture;

final class FixtureMissingPlayerJsonMapper
{
    private Response $response;

    /**
     * @param array<string, mixed> $data
     */
    public function __construct(array $data, private ?PlayerBuilder $builder = null)
    {
        $this->response = new Response($data);
    }

    public function transformToFixtureMissingPlayer(): TeamMissingPlayer
    {
        return new TeamMissingPlayer(
            (new PlayerResponseJsonMapper($this->response->get('player'), [], $this->builder))->toDataTransferObject(),
            new ReasonForMissingFixture($this->determineReasonForMissingFixture($this->response->get('player.reason')))
        );
    }

    /**
     * @todo Gather more info from apisports all possible reasons types
     */
    private function determineReasonForMissingFixture(string $reason): string
    {
        if (inArray($reason, ['Broken ankle', 'Illness', 'Knock'])) {
            return ReasonForMissingFixture::INJURED;
        }

        if (str_contains($reason, 'Injury')) {
            return ReasonForMissingFixture::INJURED;
        }

        if ($reason === 'Suspended') {
            return ReasonForMissingFixture::SUSPENDED;
        }

        return ReasonForMissingFixture::DOUBTFUL;
    }
}
