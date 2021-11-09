<?php

declare(strict_types=1);

namespace Module\User\Dto\Builders;

use App\DTO\Builder;
use Module\User\Dto\User;
use App\ValueObjects\Email;
use Module\User\Routes\Config;
use Module\User\Database\Column;
use Module\User\ValueObjects\UserId;
use Module\User\ValueObjects\Username;
use Module\User\Models\User as UserModel;

final class UserBuilder extends Builder
{
    public static function fromAuthUser(): self
    {
        return self::fromModel(auth(Config::GUARD)->user());
    }

    public static function fromModel(UserModel $model): self
    {
        $attributes = $model->getAttributes();

        $exists = fn (string $key): bool => array_key_exists($key, $attributes);

        return (new self)
            ->when($exists(Column::ID), fn (UserBuilder $b) => $b->setId($attributes[Column::ID]))
            ->when($exists(Column::NAME), fn (UserBuilder $b) => $b->setName($attributes[Column::NAME]))
            ->when($exists(Column::USERNAME), fn (UserBuilder $b) => $b->setUsername($attributes[Column::USERNAME]))
            ->when($exists(Column::EMAIL), fn (UserBuilder $b) => $b->setEmail($attributes[Column::EMAIL]))
            ->when($exists(Column::PASSWORD), fn (UserBuilder $b) => $b->setPassword($attributes[Column::PASSWORD]))
            ->when($exists(Column::IS_PRIVATE), fn (UserBuilder $b) => $b->setIsPrivate($model->getAttribute(Column::IS_PRIVATE)))
            ->when($exists(Column::FAVOURITES_COUNT), fn (UserBuilder $b) => $b->setFavouritesCount($model->getAttribute(Column::FAVOURITES_COUNT)));
    }

    public function setId(int $id): self
    {
        return $this->set('id', new UserId($id));
    }

    public function setName(string $name): self
    {
        return $this->set('name', $name);
    }

    public function setUsername(string $username): self
    {
        return $this->set('username', Username::fromString($username));
    }

    public function setEmail(string $email): self
    {
        return $this->set('email', Email::fromString($email));
    }

    public function setPassword(string $password): self
    {
        return $this->set('password', $password);
    }

    public function setIsPrivate(bool $isPrivate): self
    {
        return $this->set('isPrivate', $isPrivate);
    }

    public function setFavouritesCount(int $count): self
    {
        return $this->set('favouritesCount', $count);
    }

    public function build(): User
    {
        return new User($this->toArray());
    }
}
