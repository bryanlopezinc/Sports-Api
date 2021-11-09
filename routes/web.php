<?php

use Illuminate\Support\Facades\Route;
use Module\Football\Tests\Stubs\ApiSports\V3\FetchFixtureByDateResponse;
use Module\Football\Tests\Stubs\ApiSports\V3\FetchLeagueFixturesByDateResponse;

Route::get('/', function () {
    $res = json_decode(FetchFixtureByDateResponse::json(), true)['response'];

    $d = collect($res)->countBy(fn(array $data) => $data['league']['id'])->all();

    dd($d);
});
