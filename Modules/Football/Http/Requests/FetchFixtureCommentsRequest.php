<?php

declare(strict_types=1);

namespace Module\Football\Http\Requests;

use App\Rules\ResourceIdRule;
use App\Utils\PaginationDataRules;
use Illuminate\Foundation\Http\FormRequest;

final class FetchFixtureCommentsRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id' => ['required', new ResourceIdRule()],

            ...PaginationDataRules::default()
        ];
    }
}
