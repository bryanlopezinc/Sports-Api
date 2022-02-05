<?php

declare(strict_types=1);

namespace Module\Football\Clients\ApiSports\V3\Response;

use Module\Football\TeamMissingPlayer;
use Module\Football\DTO\Builders\PlayerBuilder;
use Module\Football\ValueObjects\ReasonForMissingFixture;

final class FixtureMissingPlayerJsonMapper
{
    /**
     * @param array<string, mixed> $data
     */
    public function __construct(private PlayerBuilder $builder = new PlayerBuilder)
    {
    }

    public function __invoke(array $data): TeamMissingPlayer
    {
        return new TeamMissingPlayer(
            (new PlayerResponseJsonMapper($data['player'], [], $this->builder))->toDataTransferObject(),
            new ReasonForMissingFixture($this->determineReasonForMissingFixture($data['player']['reason']))
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
