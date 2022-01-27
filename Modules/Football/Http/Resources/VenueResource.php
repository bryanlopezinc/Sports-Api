<?php

declare(strict_types=1);

namespace Module\Football\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Module\Football\Venue;

final class VenueResource extends JsonResource
{
    public function __construct(private Venue $venue)
    {
        parent::__construct($venue);
    }

    /**
     * @param Request $request
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'type'          => 'football_venue',
            'attributes'    => [
                'name'          => $this->venue->name->value(),
                'city'          => $this->venue->city,
            ],
        ];
    }
}
