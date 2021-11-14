<?php

declare(strict_types=1);

namespace Module\Football\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Module\Football\ValueObjects\LeagueTopAssist;

final class LeagueTopAssistResource extends JsonResource
{
    public function __construct(private LeagueTopAssist $topAssist)
    {
        parent::__construct($topAssist);
    }

    /**
     * @param Request $request
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'type'          => 'football_league_topAssist',
            'player'        => new PlayerResource($this->topAssist->player()),
            'asists'        => $this->topAssist->assists()
        ];
    }
}
