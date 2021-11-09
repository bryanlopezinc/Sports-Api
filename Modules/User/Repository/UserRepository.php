<?php

declare(strict_types=1);

namespace Module\User\Repository;

use Module\User\Dto\User;
use App\ValueObjects\Email;
use Module\User\QueryFields;
use Module\User\Database\Column;
use Module\User\Models\User as Model;
use Module\User\ValueObjects\Username;
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
        return $this->findManyBy(Column::ID, $ids->toIntegerArray(), $options);
    }

    public function findUsersByEmail(array $emails, QueryFields $options): UsersCollection
    {
        $emails = array_map(fn (Email $email): string => $email->toString(), $emails);

        return $this->findManyBy(Column::EMAIL, $emails, $options);
    }

    public function findUsersByUsername(array $usernames, QueryFields $options): UsersCollection
    {
        $usernames = array_map(fn (Username $username): string => $username->toString(), $usernames);

        return $this->findManyBy(Column::USERNAME, $usernames, $options);
    }

    /**
     * @param array<string|int> $values
     */
    private function findManyBy(string $column, array $values, QueryFields $options): UsersCollection
    {
        $results = Model::WithQueryOptions($options)
            ->whereIn($this->model->qualifyColumn($column), $values)
            ->get()
            ->map(fn (Model $model) => $this->clean($model, $options));

        return $results->isEmpty() ? new UsersCollection([]) : new UsersCollection($this->mapResultsToDto($results->all()));
    }

    public function create(User $user): User
    {
        if (password_get_info($user->password())['algoName'] === 'unknown') {
            throw new PasswordNotHashedException();
        }

        $createdUser = $this->model->create([
            Column::USERNAME    => $user->username()->toString(),
            Column::EMAIL       => $user->email()->toString(),
            Column::IS_PRIVATE  => $user->profileIsPrivate(),
            Column::NAME        => $user->name(),
            Column::PASSWORD    => $user->password(),
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

        if (!$fields->has($fields::ID)) {
            $model->offsetUnset(Column::ID);
        }

        return $model;
    }
}
