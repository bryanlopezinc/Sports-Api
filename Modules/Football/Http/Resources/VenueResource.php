<?php

declare(strict_types=1);

namespace Module\Football\Http\Resources;

use Illuminate\Http\Request;
use Module\Football\DTO\Venue;
use Illuminate\Http\Resources\MissingValue;
use App\Utils\RescueInitializationException;
use Illuminate\Http\Resources\Json\JsonResource;

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
        $rescuer = new RescueInitializationException(new MissingValue);

        return [
            'type'          => 'football_venue',
            'attributes'    => [
                'name'          => $this->venue->getName(),
                'city'          => $rescuer->rescue(fn () => $this->venue->getCityName()),
            ],
        ];
    }
}
