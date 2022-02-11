<?php

declare(strict_types=1);

namespace Module\User\Http\Requests;

use App\Rules\ResourceIdRule;
use App\Utils\PaginationDataRules;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Module\User\Routes\RouteName;

final class FetchUserPredictionsRequest extends FormRequest
{
    public const MAX_PER_PAGE = 50;

    public function rules(): array
    {
        return [
            'id' => [Rule::when($this->routeIs(RouteName::USER_PREDICtions), ['required', new ResourceIdRule])],

            ...PaginationDataRules::new()->maxPerPage(self::MAX_PER_PAGE)->toArray()
        ];
    }
}
