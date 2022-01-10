<?php

declare(strict_types=1);

namespace Module\Football\Http\Requests;

use App\Rules\ResourceIdRule;
use Illuminate\Foundation\Http\FormRequest;
use Module\Football\Rules\PartialFixtureFieldsRule;
use Module\Football\Rules\SeasonRule;

class FetchLeagueFixturesByDateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'date'        => ['required', 'date', 'date_format:Y-m-d'],
            'season'      => ['required', new SeasonRule],
            'league_id'   => ['required', new ResourceIdRule()],
            'filter'      => ['sometimes', 'filled', new PartialFixtureFieldsRule]
        ];
    }
}
