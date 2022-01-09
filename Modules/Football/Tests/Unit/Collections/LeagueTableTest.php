<?php

declare(strict_types=1);

namespace Module\Football\Tests\Unit\Collections;

use Tests\TestCase;
use InvalidArgumentException;
use Module\Football\DTO\League;
use Module\Football\DTO\LeagueStanding;
use Module\Football\Collections\LeagueTable;
use Module\Football\Factories\LeagueFactory;
use Module\Football\Factories\LeagueStandingFactory;
use Module\Football\DTO\Builders\LeagueStandingBuilder;

class LeagueTableTest extends TestCase
{
    public function test_table_standings_must_contain_same_league(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $standings = $this->generateStandings(5);

        $standings[] = LeagueStandingFactory::new()->withState(fn (LeagueStandingBuilder $b) => $b->setTeamRank(6)->setLeague(LeagueFactory::new()->toDto()))->toDto();

        new LeagueTable($standings);
    }

    /**
     * @return array<LeagueStanding>
     */
    private function generateStandings(int $count, League $league = null): array
    {
        $rank = 1;
        $league = $league ?: LeagueFactory::new()->toDto();

        return collect()->times($count, function () use (&$rank, $league) {
            $dto = LeagueStandingFactory::new()->withState(fn (LeagueStandingBuilder $b) => $b->setTeamRank($rank)->setLeague($league))->toDto();
            $rank++;

            return $dto;
        })->all();
    }
}
