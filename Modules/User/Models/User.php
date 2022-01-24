<?php

declare(strict_types=1);

namespace Module\User\Models;

use Module\User\QueryFields;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Module\User\Favourites\Models\Favourite;

/**
 * @method static Builder WithQueryOptions(QueryFields $queryOptions)
 */
class User extends Authenticatable
{
    use HasFactory,
        HasApiTokens,
        ScopeFavouritesCount;

    protected $guarded = [];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'is_private'       => 'boolean'
    ];

    /**
     * @param Builder  $builder
     *
     * @return Builder
     */
    public function scopeWithQueryOptions($builder, QueryFields $queryOptions)
    {
        $builder->addSelect($this->getQualifiedKeyName());

        if ($queryOptions->isEmpty()) {
            $builder->addSelect('users.*');
        }

        if (!$queryOptions->isEmpty()) {
            $builder->addSelect($this->qualifyColumns($queryOptions->except('favourites_count')));
        }

        $this->parseFavouritesCountQuery($builder, $queryOptions);

        return $builder;
    }

    public function getFavouritesCountAttribute(): int
    {
        return (int) $this->getAttributes()['favourites_count'];
    }

    public function favourites(): HasMany
    {
        return $this->hasMany(Favourite::class, 'user_id', 'id');
    }

    public function findForPassport(string $username): ?self
    {
        return $this->where('username', $username)->first();
    }
}
