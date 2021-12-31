<?php

declare(strict_types=1);

namespace Module\Football\Clients\ApiSports\V3\Response;

use App\ValueObjects\Date;
use Illuminate\Support\Collection;
use Module\Football\PlayerTransferRecord;
use Module\Football\PlayerTransferHistory;

final class PlayerTransferHistoryJsonMapper
{
    public function __construct(private array $response)
    {
    }

    public function map(): PlayerTransferHistory
    {
        $response = new Response($this->response);

        return new PlayerTransferHistory(
            (new PlayerResponseJsonMapper($response->get('player')))->toDataTransferObject(),
            $this->mapTransfers($response->get('transfers'))
        );
    }

    /**
     * @return array<PlayerTransferRecord>
     */
    private function mapTransfers(array $data): array
    {
        return Collection::make($data)
            ->reject(function (array $record) {
                $response = new Response($record);

                return is_null($response->get('teams.out.id')) && is_null($response->get('teams.in.id'));
            })
            ->map(function (array $record) {
                $response = new Response($record);

                $teamLeftIsKnown = !is_null($response->get('teams.out.id'));
                $teamJoinedIsKnown = !is_null($response->get('teams.in.id'));

                return new PlayerTransferRecord(
                    new Date($response->get('date')),
                    $teamLeftIsKnown ? (new TeamJsonMapper($response->get('teams.out')))->toDataTransferObject() : null,
                    $teamJoinedIsKnown ? (new TeamJsonMapper($response->get('teams.in')))->toDataTransferObject() : null,
                );
            })->all();
    }
}
