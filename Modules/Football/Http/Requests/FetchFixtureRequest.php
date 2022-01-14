<?php

declare(strict_types=1);

namespace Module\Football\Http\Requests;

use App\Rules\ResourceIdRule;
use Illuminate\Foundation\Http\FormRequest;
use Module\Football\Rules\TimeZoneRule;
use Module\Football\Rules\PartialFixtureFieldsRuleForFetchFixtureRequest;
use Module\Football\Rules\PartialLeagueFieldsRule;

final class FetchFixtureRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id'            => ['required', new ResourceIdRule()],
            'timezone'      => ['sometimes', 'string', new TimeZoneRule],
            'filter'        => ['sometimes', 'filled', 'string', new PartialFixtureFieldsRuleForFetchFixtureRequest],
            'league_filter' => ['sometimes', 'filled', 'string', new PartialLeagueFieldsRule]
        ];
    }
}
