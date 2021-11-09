<?php

declare(strict_types=1);

namespace Module\User\Tests\Unit\Repository;

use Tests\TestCase;
use Module\User\QueryFields;
use Module\User\ValueObjects\UserId;
use Module\User\Factories\UserFactory;
use Module\User\Dto\Builders\UserBuilder;
use Module\User\Repository\UserRepository;
use Module\User\Exceptions\PasswordNotHashedException;

class UserRepositoryTest extends TestCase
{
    private UserRepository $repository;

    public function setUp(): void
    {
        parent::setUp();

        $this->repository = new UserRepository;
    }

    public function test_will_throw_exception_when_trying_to_store_plain_password(): void
    {
        $this->expectException(PasswordNotHashedException::class);

        $this->repository->create(
            UserBuilder::fromModel(UserFactory::new()->make()) // @phpstan-ignore-line
                ->setPassword('I dont want to follow security standards because i know it all :)')
                ->build()
        );
    }

    public function test_will_return_only_requested_attributes(): void
    {
        $user = UserFactory::new()->create();

        $options = QueryFields::builder()->email()->build();

        $id = $user->id; // @phpstan-ignore-line

        $result = $this->repository->findUsersById((new UserId($id))->asCollection(), $options)->sole();

        $this->assertCount(1, $result->toArray());

        //ensures only email was retrieved. will throw initialization exception if not.
        $result->email();
    }
}
