<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FootballPredictionCodesTable extends Migration
{
    public function up(): void
    {
        Schema::create('football_prediction_codes', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('description');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('football_prediction_codes');
    }
}
