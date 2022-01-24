<?php

declare(strict_types=1);

namespace Module\User\Http\Requests;

use App\Utils\Config;
use Illuminate\Foundation\Http\FormRequest;
use Module\User\Rules\UsernameRule;
use Illuminate\Validation\Rules\Password;

final class CreateUserRequest extends FormRequest
{
    /**
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'email'          => ['bail', 'email', 'required', 'unique:users,email'],
            'is_private'     => ['bail', 'nullable', 'bool'],
            'name'           => ['bail', 'required', 'max:' . Config::get('user.displaynameMaxLength'),],
            'password'       => ['bail', 'required', Password::min(8), 'confirmed'],
            'username'       => ['bail', 'required', new UsernameRule, 'unique:users,username'],
        ];
    }
}
