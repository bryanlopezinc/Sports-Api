<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFixturesResultTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('football_fixtures_results', function (Blueprint $table) {
            $table->id();
            $table->integer('fixture_id')->unique();
            $table->integer('home_team_id');
            $table->integer('away_team_id');
            $table->integer('status');
            $table->integer('winner_id')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('football_fixtures_results');
    }
}
