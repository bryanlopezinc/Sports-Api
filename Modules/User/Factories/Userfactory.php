<?php

declare(strict_types=1);

namespace Module\User\Factories;

use App\Utils\Config;
use Illuminate\Support\Str;
use Module\User\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

final class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [
            'email'      => $this->faker->safeEmail,
            'is_private' => false,
            'name'       => (string) Str::of($this->faker->name)->limit(Config::get('user.displaynameMaxLength'), ''),
            'password'   => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password,
            'username'   => Str::random(Config::get('user.usernameMaxLength')),
        ];
    }

    public function private(): self
    {
        return $this->state(function (array $attributes) {
            $attributes['is_private'] = true;
            return $attributes;
        });
    }
}
