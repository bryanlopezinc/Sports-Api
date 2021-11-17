<?php

declare(strict_types=1);

namespace Module\Football\Http\Resources;

use Illuminate\Http\Request;
use Module\Football\DTO\Coach;
use App\Http\Resources\CountryResource;
use Illuminate\Http\Resources\MissingValue;
use Module\Football\Routes\FetchCoachRoute;
use App\Utils\RescueInitializationException;
use Illuminate\Http\Resources\Json\JsonResource;

final class CoachResource extends JsonResource
{
    public function __construct(private Coach $coach)
    {
        parent::__construct($coach);
    }

    /**
     * @param Request $request
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        $rescuer = new RescueInitializationException(new MissingValue);

        return [
            'type'       => 'football_coach',
            'attributes' => [
                'id'            => $this->coach->id()->toInt(),
                'name'          => $rescuer->rescue(fn () => $this->coach->name()->value()),
                'photo_url'     => $rescuer->rescue(fn () => $this->coach->photoUrl()->url()),
                'team'          => $rescuer->rescue(fn () => $this->when($this->coach->hasCurrentTeam(), fn () => new TeamResource($this->coach->currentTeam()))),
                'has_team'      => $rescuer->rescue(fn () => $this->coach->hasCurrentTeam()),
                'age'           => $rescuer->rescue(fn () => $this->coach->age()->toInt()),
                'nationality'   => $rescuer->rescue(fn () => new CountryResource($this->coach->nationality()))
            ],
            'links'     => [
                'self'  => new FetchCoachRoute($this->coach->id())
            ]
        ];
    }
}
