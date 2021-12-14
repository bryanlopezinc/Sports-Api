<?php

declare(strict_types=1);

namespace Module\Football\Http\Requests;

use App\Rules\ResourceIdRule;
use App\Http\Requests\FormRequest;
use Module\Football\Rules\SeasonRule;
use Module\Football\Rules\PartialLeagueFieldsRule;

final class FetchLeagueRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id'       => ['required', 'int', new ResourceIdRule],
            'season'   => ['nullable', new SeasonRule],
            'filter'   => ['sometimes', 'string', new PartialLeagueFieldsRule]
        ];
    }
}
