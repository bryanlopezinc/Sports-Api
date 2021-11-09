<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UserFavouriteTypeTable extends Migration
{
    public function up(): void
    {
        Schema::create('user_favourite_type', function (Blueprint $table) {
            $table->id();
            $table->string('type')->index();
            $table->string('sports_type')->index();
            $table->unique(['type', 'sports_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_favourite_type');
    }
}
