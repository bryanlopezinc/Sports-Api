<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UserFavouritesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_favourites', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('type_id')->index();
            $table->integer('favourite_id')->index();
            $table->unique(['user_id', 'type_id', 'favourite_id']);
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
        Schema::dropIfExists('users_favourites');
    }
}
