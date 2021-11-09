<?php

declare(strict_types=1);

namespace Module\User\Models;

use Module\User\Database\Column;

trait ScopeFavouritesCount
{
    /**
     * @param \Illuminate\Database\Eloquent\Builder  $builder
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function parseFavouritesCountQuery(&$builder, ColumnsRequest $request)
    {
        $wantsFavoritesCount = $request->wantsFavouritesCountRelation() ?: $request->gueryFields()->isEmpty();

        if (!$wantsFavoritesCount) {
            return $builder;
        }

        return $builder->join('users_favourites_count', 'users.id', '=', 'users_favourites_count.user_id', 'left outer')
            ->addSelect([
                'users_favourites_count.user_id',
                'users_favourites_count.count as ' . Column::FAVOURITES_COUNT,
            ]);
    }
}
