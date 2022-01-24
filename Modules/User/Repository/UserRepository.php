<?php

declare(strict_types=1);

namespace Module\User\Repository;

use Illuminate\Support\Collection;
use Module\User\Dto\User;
use Module\User\QueryFields;
use Module\User\Models\User as Model;
use Module\User\Dto\Builders\UserBuilder;
use Module\User\Collections\UsersCollection;
use Module\User\Collections\UserIdsCollection;
use Module\User\Exceptions\PasswordNotHashedException;
use Module\User\Contracts\CreateUserRepositoryInterface;
use Module\User\Contracts\FetchUsersRepositoryInterface;

final class UserRepository implements FetchUsersRepositoryInterface, CreateUserRepositoryInterface
{
    private Model $model;

    public function __construct(Model $model = null)
    {
        $this->model = $model ?: new Model;
    }

    public function findUsersById(UserIdsCollection $ids, QueryFields $options): UsersCollection
    {
        return Model::WithQueryOptions($options)
            ->whereIn($this->model->qualifyColumn('id'), $ids->toIntegerArray())
            ->get()
            ->map(fn (Model $model) => $this->clean($model, $options))
            ->pipe(fn(Collection $collection) => new UsersCollection($this->mapResultsToDto($collection->all())));
    }

    public function create(User $user): User
    {
        if (password_get_info($user->password())['algoName'] === 'unknown') {
            throw new PasswordNotHashedException();
        }

        $createdUser = $this->model->create([
            'username'    => $user->username()->toString(),
            'email'       => $user->email()->toString(),
            'is_private'  => $user->profileIsPrivate(),
            'name'        => $user->name(),
            'password'    => $user->password(),
        ]);

        return UserBuilder::fromModel($createdUser)->setFavouritesCount(0)->build();
    }

    /**
     * @param array<Model> $results
     * @return array<User>
     */
    private function mapResultsToDto(array $results): array
    {
        return array_map(fn (Model $user): User => UserBuilder::fromModel($user)->build(), $results);
    }

    private function clean(Model $model, QueryFields $fields): model
    {
        if ($fields->isEmpty()) {
            return $model;
        }

        if (!$fields->has('id')) {
            $model->offsetUnset('id');
        }

        return $model;
    }
}
