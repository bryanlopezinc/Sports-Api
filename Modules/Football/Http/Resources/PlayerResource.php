<?php

declare(strict_types=1);

namespace Module\Football\Http\Resources;

use Illuminate\Http\Request;
use Module\Football\DTO\Player;
use Illuminate\Http\Resources\MissingValue;
use App\Utils\RescueInitializationException;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\CountryResource;

class PlayerResource extends JsonResource
{
    public function __construct(private Player $player)
    {
        parent::__construct($player);
    }

    /**
     * @param Request $request
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        $rescuer = new RescueInitializationException(new MissingValue);

        return [
            'type'           => 'football_player',
            'attributes'     => [
                'id'             => $this->player->getId()->toInt(),
                'name'           => $this->player->name(),
                'photo_url'      => $rescuer->rescue(fn () => $this->player->photoUrl()->url()),
                'date_of_birth'  => $rescuer->rescue(fn () => $this->player->birthDate()->toCarbon()->toDateString()),
                'height_cm'      => $rescuer->rescue(fn () => $this->player->height()->height()),
                'country'        => $rescuer->rescue(fn () => new CountryResource($this->player->nationality())),
                'position'       => $rescuer->rescue(fn () => $this->tranformPlayerPosition()),
                'has_shirt_no'   => $rescuer->rescue(fn () => $this->player->JerseyNumber()->isKnown()),
                'shirt_no'       => $rescuer->rescue(fn () => $this->when($this->player->JerseyNumber()->isKnown(), fn () => $this->player->JerseyNumber()->number())),
                'age'            => $rescuer->rescue(fn () => $this->player->age()->toInt()),
            ]
        ];
    }

    private function tranformPlayerPosition(): string
    {
        $playerPosition = $this->player->getPosition();

        return match (true) {
            $playerPosition->isGoalKeeper()    => 'GK',
            $playerPosition->isDefender()      => 'DF',
            $playerPosition->isMiddlFielder()  => 'MF',
            $playerPosition->isAttacker()      => 'FW'
        };
    }
}
