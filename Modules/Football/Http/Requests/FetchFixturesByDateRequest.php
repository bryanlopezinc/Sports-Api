<?php

declare(strict_types=1);

namespace Module\Football\Http\Requests;

use App\Http\Requests\FormRequest;
use Module\Football\Rules\TimeZoneRule;

class FetchFixturesByDateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'date'      => ['required', 'date', 'date_format:Y-m-d'],
            'timezone'  => ['sometimes', 'string', new TimeZoneRule]
        ];
    }
}
