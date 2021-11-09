<?php

declare(strict_types=1);

namespace Module\User\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

final class UserFavourite extends Model
{
    const UPDATED_AT = null;

    protected $table = 'users_favourites';
    protected $guarded = [];

    public function type(): HasOne
    {
        return $this->hasOne(UserFavouriteType::class, 'id', 'type_id');
    }
}
