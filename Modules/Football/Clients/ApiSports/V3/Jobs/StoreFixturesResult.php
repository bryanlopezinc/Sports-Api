<?php

declare(strict_types=1);

namespace Module\Football\Clients\ApiSports\V3\Jobs;

use App\ValueObjects\Date;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\LazyCollection;
use Module\Football\Clients\ApiSports\V3\FetchFixturesByDateHttpClient;
use Module\Football\Clients\ApiSports\V3\Response\FixtureResponseJsonMapper;

final class StoreFixturesResult
{
    use Dispatchable, InteractsWithQueue, Queueable;

    public function handle(FetchFixturesByDateHttpClient $client): void
    {
        $jsonMapper = new FixtureResponseJsonMapper();

        // fetch fixtures older than 24hrs to ensure all fixtures are concluded.
        $client->fetchFixturesByDate(new Date(today()->subDay()->toDateString()))
            ->chunk(50)
            ->each(function (LazyCollection $chunk) use ($jsonMapper): void {
                $values = $chunk->map(fn (array $data) => [
                    'fixture_id'    => $data['fixture']['id'],
                    'home_team_id'  => $data['teams']['home']['id'],
                    'away_team_id'  => $data['teams']['away']['id'],
                    'status'        => $jsonMapper->convertFixtureStatus($data),
                    'winner_id'     => $jsonMapper->getWinner($data)?->getId()?->toInt()
                ]);

                DB::table('football_fixtures_results')->insert($values->all());
            });
    }
}
