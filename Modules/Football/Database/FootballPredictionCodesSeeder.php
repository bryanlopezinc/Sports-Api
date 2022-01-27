<?php

namespace Module\Football\Database;

use Illuminate\Database\Seeder;
use Module\Football\Prediction\Models\PredictionCode;

class FootballPredictionCodesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PredictionCode::insert([
            [
                'code' => PredictionCode::HOME_WIN,
                'description' => 'Home team to win fixture'
            ],
            [
                'code' => PredictionCode::AWAY_WIN,
                'description' => 'Away team to win fixture'
            ],
            [
                'code' => PredictionCode::DRAW,
                'description' => 'Fixture outcome to be draw'
            ]
        ]);
    }
}
