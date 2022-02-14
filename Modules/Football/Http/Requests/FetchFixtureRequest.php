<?php

declare(strict_types=1);

namespace Module\Football\Http\Requests;

use App\Rules\ResourceIdRule;
use Illuminate\Foundation\Http\FormRequest;
use Module\Football\Rules\TimeZoneRule;
use Module\Football\Rules\FixtureFieldsRuleForFetchFixtureRequest;
use Module\Football\Rules\LeagueFieldsRule;

final class FetchFixtureRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id'            => ['required', new ResourceIdRule()],
            'timezone'      => ['sometimes', 'string', new TimeZoneRule],
            'filter'        => ['sometimes', 'filled', new FixtureFieldsRuleForFetchFixtureRequest],
            'league_filter' => ['sometimes', 'filled', 'string', new LeagueFieldsRule]
        ];
    }
}
