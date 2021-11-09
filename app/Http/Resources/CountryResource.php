<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\ValueObjects\Country;
use Illuminate\Http\Resources\Json\JsonResource;

final class CountryResource extends JsonResource
{
    public function __construct(private Country $country)
    {
        parent::__construct($country);
    }

    /**
     * @param Request $request
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'name'      =>  $this->country->name()
        ];
    }
}
