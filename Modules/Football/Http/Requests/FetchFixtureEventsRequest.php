<?php

declare(strict_types=1);

namespace Module\Football\Http\Requests;

use App\Rules\ResourceIdRule;
use App\Http\Requests\FormRequest;

final class FetchFixtureEventsRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id' => ['required', new ResourceIdRule],
        ];
    }
}
