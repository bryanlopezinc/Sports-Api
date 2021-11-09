<?php

declare(strict_types=1);

namespace Module\User\Http\Requests;

use App\Utils\PaginationData;
use App\Rules\ResourceIdRule;
use Module\User\Routes\RouteName;
use App\Http\Requests\FormRequest;
use Illuminate\Validation\Rules\RequiredIf;

final class UserFavouritesRequest extends FormRequest
{
    /**
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'id'       => [new RequiredIf($this->routeIs(RouteName::FAVOURITES)), new ResourceIdRule],
            'page'     => ['nullable', 'int', 'max:' . PaginationData::MAX_PAGE],
            'per_page' => ['nullable', 'int', 'max:' . PaginationData::MAX_PER_PAGE]
        ];
    }
}
