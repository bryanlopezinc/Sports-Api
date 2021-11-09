<?php

declare(strict_types=1);

namespace Module\User\Http\Requests;

use App\Rules\ResourceIdRule;
use App\Http\Requests\FormRequest;

final class AddUserFavouriteRequest extends FormRequest
{
    /**
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'id'    => ['required', 'int', new ResourceIdRule],
        ];
    }
}
