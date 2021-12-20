<?php

declare(strict_types=1);

namespace Module\User\Favourites\Football;

use App\Rules\ResourceIdRule;
use Illuminate\Foundation\Http\FormRequest;

final class AddResourceToFavouritesRequest extends FormRequest
{
    /**
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'id'    => ['required', new ResourceIdRule()],
        ];
    }
}
