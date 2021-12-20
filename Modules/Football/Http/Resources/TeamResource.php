<?php

declare(strict_types=1);

namespace Module\Football\Http\Resources;

use Illuminate\Http\Request;
use Module\Football\DTO\Team;
use App\Http\Resources\CountryResource;
use Module\Football\Routes\FetchTeamRoute;
use Illuminate\Http\Resources\MissingValue;
use App\Utils\RescueInitializationException;
use Illuminate\Http\Resources\Json\JsonResource;

final class TeamResource extends JsonResource
{
    public function __construct(protected Team $team)
    {
        parent::__construct($team);
    }

    /**
     * @param Request $request
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        $rescuer = new RescueInitializationException(new MissingValue);

        return [
            'type'              => 'football_team',
            'attributes'        => [
                'id'                     => $this->team->getId()->asHashedId(),
                'name'                   => $this->team->getName()->value(),
                'logo_url'               => $rescuer->rescue(fn () => $this->team->getLogoUrl()->toString()),
                'has_year_founded_info'  => $rescuer->rescue(fn () => $this->team->hasYearFoundedInfo()),
                'year_founded'           => $this->getYearFoundedInfo($rescuer),
                'country'                => $rescuer->rescue(fn () => new CountryResource($this->team->getCountry())),
                'venue'                  => $rescuer->rescue(fn () => new VenueResource($this->team->getVenue())),
            ],
            'links'     => [
                'self'  => (string) new FetchTeamRoute($this->team->getId())
            ]
        ];
    }

    private function getYearFoundedInfo(RescueInitializationException $rescuer): MissingValue|int
    {
        return $rescuer->rescue(function () {
            return $this->when($this->team->hasYearFoundedInfo(), fn () => $this->team->getYearFounded()->toInt());
        });
    }
}
