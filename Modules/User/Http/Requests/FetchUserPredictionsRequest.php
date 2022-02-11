<?php

declare(strict_types=1);

namespace Module\User\Http\Requests;

use App\Rules\ResourceIdRule;
use App\Utils\PaginationData;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Module\User\Routes\RouteName;

final class FetchUserPredictionsRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id'        => [Rule::when($this->routeIs(RouteName::USER_PREDICtions), ['required', new ResourceIdRule])],
            'page'      => ['nullable', 'int', 'min:1', 'max:' . PaginationData::MAX_PAGE],
            'per_page'  => [
                'nullable',
                'int',
                'min:' . PaginationData::MIN_PER_PAGE,
                'max:' . PaginationData::MAX_PER_PAGE
            ]
        ];
    }
}
