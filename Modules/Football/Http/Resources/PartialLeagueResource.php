<?php

declare(strict_types=1);

namespace Module\Football\Http\Resources;

use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Module\Football\DTO\League;
use Module\Football\Http\PartialLeagueRequest;
use Illuminate\Http\Resources\Json\JsonResource;

final class PartialLeagueResource extends JsonResource
{
    public function __construct(private League $league, private string $filterInputName)
    {
        parent::__construct($league);
    }

    /**
     * @param Request $httpRequest
     * @return array<string, mixed>
     */
    public function toArray($httpRequest)
    {
        $originalResponse  = (new LeagueResource($this->league))->toArray($httpRequest);
        $request = PartialLeagueRequest::fromRequest($httpRequest, $this->filterInputName);

        if ($request->isEmpty()) {
            return $originalResponse;
        }

        //All the attributes that will be set in the attributes level.
        $customAttributes = [];

        //Get all requested attributes except attributes not in the attributes level in league resource
        foreach ($request->all(['links']) as $key) {
            Arr::set($customAttributes, $key, Arr::get($originalResponse['attributes'], $key));
        }

        Arr::set($originalResponse, 'attributes', $customAttributes);

        if (!$request->has('links')) {
            Arr::forget($originalResponse, 'links');
        }

        return $originalResponse;
    }
}
