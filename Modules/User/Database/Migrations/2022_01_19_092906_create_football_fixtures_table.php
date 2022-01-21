<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFootballFixturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('football_fixtures', function (Blueprint $table) {
            $table->id();
            $table->integer('fixture_id')->unique();
            $table->integer('home_team_id')->index();
            $table->integer('away_team_id')->index();
            $table->integer('league_id')->index();
            $table->date('date')->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('football_fixtures');
    }
}
