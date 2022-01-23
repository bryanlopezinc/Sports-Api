<?php

declare(strict_types=1);

namespace Module\Football\Tests\Unit\Favourites;

use App\ValueObjects\Uid;
use Tests\TestCase;
use Module\Football\Favourites\Exceptions\DuplicateEntryException;
use Module\Football\Favourites\Models\Favourite;
use Module\Football\Favourites\Repository;
use Module\Football\ValueObjects\LeagueId;
use Module\Football\ValueObjects\TeamId;
use Module\User\ValueObjects\UserId;

class RepositoryTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Favourite::truncate();
    }

    public function test_will_throw_exception_add_adding_team_record_that_exists(): void
    {
        $this->expectException(DuplicateEntryException::class);

        $repository = new Repository;

        $repository->addTeam(new TeamId(22), new UserId(30), Uid::generate());
        $repository->addTeam(new TeamId(22), new UserId(30), Uid::generate());
    }

    public function test_will_throw_exception_add_adding_league_record_that_exists(): void
    {
        $this->expectException(DuplicateEntryException::class);

        $repository = new Repository;

        $repository->addLeague(new LeagueId(22), new UserId(30), Uid::generate());
        $repository->addLeague(new LeagueId(22), new UserId(30), Uid::generate());
    }

    public function test_will_save_record(): void
    {
        $this->expectNotToPerformAssertions();

        $repository = new Repository;

        $repository->addLeague(new LeagueId(220), new UserId(30), Uid::generate());
        $repository->addTeam(new TeamId(409), new UserId(30), Uid::generate());
    }
}
