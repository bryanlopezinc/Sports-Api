<?php

declare(strict_types=1);

namespace Module\Football\Http\Requests;

use App\Http\Requests\FormRequest;
use App\Rules\ResourceIdRule;

final class FetchFixtureLineUpRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id' => ['required', new ResourceIdRule],
        ];
    }
}
