<?php

declare(strict_types=1);

namespace Module\User\Favourites\Models;

use Illuminate\Database\Eloquent\Model;

final class Favourite extends Model
{
    const UPDATED_AT = null;

    protected $table = 'users_favourites';
    protected $guarded = [];
}
