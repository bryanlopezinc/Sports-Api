<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Module\Football\Media\Controller;

Route::prefix('football')->group(function () {
    Route::get('coach/{id}', [Controller::class, 'coach'])->name('coach.photo')->whereAlphaNumeric('id');
    Route::get('league-logo/{id}', [Controller::class, 'leagueLogo'])->name('league.logo')->whereAlphaNumeric('id');
    Route::get('player/{id}', [Controller::class, 'player'])->name('player.photo')->whereAlphaNumeric('id');
    Route::get('team-logo/{id}', [Controller::class, 'teamLogo'])->name('team.logo')->whereAlphaNumeric('id');
});
