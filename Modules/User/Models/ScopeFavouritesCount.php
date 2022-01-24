<?php

declare(strict_types=1);

namespace Module\User\Models;

use Module\User\QueryFields;

trait ScopeFavouritesCount
{
    /**
     * @param \Illuminate\Database\Eloquent\Builder  $builder
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function parseFavouritesCountQuery(&$builder, QueryFields $options)
    {
        $wantsFavoritesCount = $options->has($options::FAVOURITES_COUNT) ?: $options->isEmpty();

        if (!$wantsFavoritesCount) {
            return $builder;
        }

        return $builder->join('users_favourites_count', 'users.id', '=', 'users_favourites_count.user_id', 'left outer')
            ->addSelect([
                'users_favourites_count.user_id',
                'users_favourites_count.count as favourites_count',
            ]);
    }
}
