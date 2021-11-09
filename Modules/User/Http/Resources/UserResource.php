<?php

declare(strict_types=1);

namespace Module\User\Http\Resources;

use JsonSerializable;
use Module\User\Dto\User;
use Illuminate\Http\Request;
use Module\User\Routes\UserFavouritesRoute;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function __construct(private User $user)
    {
        parent::__construct($user);
    }

    /**
     * @param Request $request
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'type'                  => 'user',
            'attributes'            => [
                'id'                    => $this->user->getId()->toInt(),
                'username'              => $this->user->username()->toString(),
                'name'                  => $this->user->name(),
                'is_private_profile'    => $this->user->profileIsPrivate(),
                'favourites_count'      => $this->user->getFavouritesCount(),
            ],
            'links'         => [
                'favourites'    =>  $this->favouritesLink(),
                'self'          =>  $this->linkToSelf(),
            ]
        ];
    }

    protected function favouritesLink(): string|JsonSerializable
    {
        return new UserFavouritesRoute($this->user->getId());
    }

    protected function linkToSelf(): string|JsonSerializable
    {
        return new UserFavouritesRoute($this->user->getId());
    }
}
