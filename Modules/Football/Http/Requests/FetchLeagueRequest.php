<?php

declare(strict_types=1);

namespace Module\Football\Http\Requests;

use App\Rules\ResourceIdRule;
use Illuminate\Foundation\Http\FormRequest;
use Module\Football\Rules\SeasonRule;
use Module\Football\Rules\LeagueFieldsRule;

final class FetchLeagueRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id'       => ['required', new ResourceIdRule()],
            'season'   => ['nullable', new SeasonRule],
            'filter'   => ['sometimes', 'filled', 'string', new LeagueFieldsRule]
        ];
    }
}
