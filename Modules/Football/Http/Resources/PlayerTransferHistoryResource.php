<?php

declare(strict_types=1);

namespace Module\Football\Http\Resources;

use Illuminate\Http\Request;
use Module\Football\PlayerTransferRecord;
use Module\Football\PlayerTransferHistory;
use Illuminate\Http\Resources\Json\JsonResource;

final class PlayerTransferHistoryResource extends JsonResource
{
    public function __construct(private PlayerTransferHistory $transferHistory)
    {
        parent::__construct($transferHistory);
    }

    /**
     * @param Request $request
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'player'     => new PlayerResource($this->transferHistory->player()),
            'transfers'  => array_map(function (PlayerTransferRecord $record): array {
                return [
                    'date'  => $record->getDate()->toCarbon()->toDateString(),
                    'team_left_is_known'   => $record->teamDepartedIsKnown(),
                    'team_joined_is_known' => $record->teamJoinedIsKnown(),
                    'team_left'            => $this->when($record->teamDepartedIsKnown(), fn () => new TeamResource($record->teamDeparted())),
                    'team_joined'          => $this->when($record->teamJoinedIsKnown(), fn () => new TeamResource($record->teamJoined())),
                ];
            }, $this->transferHistory->transfers()),
        ];
    }
}
