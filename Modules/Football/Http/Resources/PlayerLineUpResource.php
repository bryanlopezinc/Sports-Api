<?php

declare(strict_types=1);

namespace Module\Football\Http\Resources;

use Illuminate\Http\Request;
use Module\Football\DTO\Player;
use Module\Football\PlayerPositionOnGrid;

final class PlayerLineUpResource extends PlayerResource
{
    public function __construct(Player $player, private PlayerPositionOnGrid $gridView)
    {
        parent::__construct($player);
    }

    /**
     * @param Request $request
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        $attributes = parent::toArray($request);

        $gridView = $this->gridView;

        data_set($attributes, 'attributes.has_grid', !$gridView->isNull());

        data_set(
            $attributes,
            'attributes.grid',
            $this->when(!$this->gridView->isNull(), fn () => [
                'row'    => $this->gridView->row(),
                'column' => $this->gridView->column()
            ])
        );

        return $attributes;
    }
}
