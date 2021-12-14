<?php

declare(strict_types=1);

namespace Module\User\Favourites\Models;

use Illuminate\Database\Eloquent\Model;

final class FavouriteCount extends Model
{
    protected $table = 'users_favourites_count';
    
    protected $guarded = [];
}
