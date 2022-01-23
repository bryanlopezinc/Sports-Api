<?php

declare(strict_types=1);

namespace Module\Football\Favourites\Controllers;

use App\Rules\ResourceIdRule;
use Illuminate\Foundation\Http\FormRequest;

final class Request extends FormRequest
{
    public function rules()
    {
        return [
            'id'    => ['required', new ResourceIdRule()],
        ];
    }
}
