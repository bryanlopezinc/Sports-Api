<?php

declare(strict_types=1);

namespace Module\Football\Http\Requests;

use App\Rules\ResourceIdRule;
use App\Http\Requests\FormRequest;
use Module\Football\Rules\TimeZoneRule;
use Module\Football\Rules\PartialFixtureFieldsRule;
use Module\Football\Rules\PartialLeagueFieldsRule;

final class FetchFixtureRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id'            => ['required', new ResourceIdRule()],
            'timezone'      => ['sometimes', 'string', new TimeZoneRule],
            'filter'        => ['sometimes', 'string', new PartialFixtureFieldsRule],
            'league_filter' => ['sometimes', 'string', new PartialLeagueFieldsRule]
        ];
    }
}
