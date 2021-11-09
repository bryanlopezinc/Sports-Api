<?php

declare(strict_types=1);

namespace Module\User\Dto;

use App\ValueObjects\Email;
use App\DTO\DataTransferObject;
use Module\User\ValueObjects\UserId;
use Module\User\ValueObjects\Username;

final class User extends DataTransferObject
{
    protected UserId $id;
    protected string $name;
    protected Username $username;
    protected Email $email;
    protected string $password;
    protected bool $isPrivate;
    protected int $favouritesCount;

    public function getId(): UserId
    {
        return $this->id;
    }

    public function getFavouritesCount(): int
    {
        return $this->favouritesCount;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function username(): Username
    {
        return $this->username;
    }

    public function email(): Email
    {
        return $this->email;
    }

    public function password(): string
    {
        return $this->password;
    }

    public function profileIsPrivate(): bool
    {
        return $this->isPrivate;
    }
}
