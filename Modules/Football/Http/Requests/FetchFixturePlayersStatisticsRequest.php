<?php

declare(strict_types=1);

namespace Module\Football\Http\Requests;

use App\Rules\ResourceIdRule;
use Illuminate\Foundation\Http\FormRequest;

final class FetchFixturePlayersStatisticsRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id'   => ['required', new ResourceIdRule],
            'team' => ['sometimes', new ResourceIdRule]
        ];
    }
}
