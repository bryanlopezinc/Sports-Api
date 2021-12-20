<?php

declare(strict_types=1);

namespace Module\User\Http\Requests;

use App\Utils\Config;
use App\ValueObjects\Email;
use Module\User\QueryFields;
use Illuminate\Foundation\Http\FormRequest;
use Module\User\Rules\UsernameRule;
use Module\User\ValueObjects\Username;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Module\User\Contracts\FetchUsersRepositoryInterface;

final class CreateUserRequest extends FormRequest
{
    /**
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'email'          => ['bail', 'email', 'required', $this->uniqueEmailRule()],
            'is_private'     => ['bail', 'nullable', 'bool'],
            'name'           => ['bail', 'required', 'max:' . Config::get('user.displaynameMaxLength'),],
            'password'       => ['bail', 'required', Password::min(8), 'confirmed'],
            'username'       => ['bail', 'required', new UsernameRule, $this->uniqueUsernameRule()],
        ];
    }

    private function uniqueEmailRule(): Rule
    {
        return new class implements Rule
        {
            public function passes($attribute, $value)
            {
                /** @var FetchUsersRepositoryInterface*/
                $repository = app(FetchUsersRepositoryInterface::class);

                return $repository->findUsersByEmail([Email::fromString($value)], QueryFields::builder()->email()->build())->isEmpty();
            }

            public function message()
            {
                return 'Email is already taken';
            }
        };
    }

    private function uniqueUsernameRule(): Rule
    {
        return new class implements Rule
        {
            public function passes($attribute, $value)
            {
                /** @var FetchUsersRepositoryInterface*/
                $repository = app(FetchUsersRepositoryInterface::class);

                return $repository->findUsersByUsername([Username::fromString($value)], QueryFields::builder()->username()->build())->isEmpty();
            }

            public function message()
            {
                return 'Username is already taken';
            }
        };
    }
}
