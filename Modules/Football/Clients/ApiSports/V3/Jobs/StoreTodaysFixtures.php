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

final class StoreTodaysFixtures
{
    use Dispatchable, InteractsWithQueue, Queueable;

    public function handle(FetchFixturesByDateHttpClient $client): void
    {
        $response = $client->fetchFixturesByDate(new Date($date = today()->toDateString()));

        $response->chunk(50)->each(function (LazyCollection $chunk) use ($date): void {
            $values = $chunk->map(fn (array $data) => [
                'fixture_id'    => $data['fixture']['id'],
                'home_team_id'  => $data['teams']['home']['id'],
                'away_team_id'  => $data['teams']['away']['id'],
                'league_id'     => $data['league']['id'],
                'date'          => $date
            ])->all();

            DB::table('football_fixtures')->insert($values);
        });
    }
}
