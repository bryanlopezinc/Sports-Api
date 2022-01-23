<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UserFavouritesCountTable extends Migration
{
    public function up(): void
    {
        Schema::create('users_favourites_count', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->unsigned()->unique();
           // $table->foreignId('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->integer('count')->unsigned();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users_favourites_count');
    }
}
