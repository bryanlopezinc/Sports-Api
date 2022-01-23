<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UserFavouritesFootballTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_favourites_football', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('type')->index();
            $table->integer('favourite_id')->index();
            $table->uuid('uid')->unique();
            $table->unique(['user_id', 'type', 'favourite_id']);
            $table->timestamp('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users_favourites_football');
    }
}
