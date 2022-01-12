<?php

declare(strict_types=1);

namespace Module\Football\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\ResourceIdRule;
use Module\Football\Rules\PartialFixtureStatisticsFieldsRule;

final class FetchFixtureStatisticsRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id'     => ['required', new ResourceIdRule],
            'team'   => ['sometimes', 'filled', new ResourceIdRule],
            'fields' => ['sometimes', 'filled', new PartialFixtureStatisticsFieldsRule],
        ];
    }
}
