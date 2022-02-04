<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FootballPredictionsTable extends Migration
{
    public function up(): void
    {
        Schema::create('football_predictions', function (Blueprint $table) {
            $table->id();
            $table->integer('fixture_id');
            $table->integer('user_id');
            $table->integer('code_id')->index();
            $table->timestamp('predicted_on')->useCurrent();
            $table->unique(['fixture_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('football_predictions');
    }
}
