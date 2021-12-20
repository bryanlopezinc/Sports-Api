<?php

declare(strict_types=1);

namespace Module\User\Http\Requests;

use App\Rules\ResourceIdRule;
use Module\User\Routes\RouteName;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\RequiredIf;

final class UserProfileRequest extends FormRequest
{
    /**
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'id'     => [new RequiredIf(! $this->routeIs(RouteName::AUTH_USER_PROFILE)), new ResourceIdRule],
        ];
    }
}
