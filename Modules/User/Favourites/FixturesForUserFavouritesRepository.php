<?php

declare(strict_types=1);

namespace Module\User\Favourites;

use App\ValueObjects\Date;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Module\Football\Collections\FixtureIdsCollection;
use Module\Football\Services\FetchFixtureService;
use Module\Football\ValueObjects\FixtureId;
use Module\User\Favourites\Models\FavouriteType;
use Module\User\ValueObjects\UserId;

final class FixturesForUserFavouritesRepository
{
    public function __construct(private FetchFixtureService $service)
    {
    }

    public function fixtures(UserId $userId, Date $date): array
    {
        $fixtureIds = collect($this->getFixtureIds($userId, $date))
            ->map(fn (int $id) => new FixtureId($id))
            ->pipe(fn (Collection $collection) => new FixtureIdsCollection($collection->all()));

        return $this->service->findMany($fixtureIds)->toArray();
    }

    /**
     * @return array<int>
     */
    public function getFixtureIds(UserId $userId, Date $date): array
    {
        /** @var Builder */
        $query = $this->model()->select('fixture_id')
            ->join('users_favourites', 'favourite_id', '=', 'home_team_id')
            ->where('type_id', function (Builder $query) {
                $query->select('id')
                    ->from('user_favourite_type')
                    ->where('type', FavouriteType::TEAM_TYPE)
                    ->where('sports_type', FavouriteType::SPORTS_TYPE_FOOTBALL);
            });

        $query->union($this->model()->select('fixture_id')
            ->join('users_favourites', 'favourite_id', '=', 'away_team_id')
            ->where('type_id', function (Builder $query) {
                $query->select('id')
                    ->from('user_favourite_type')
                    ->where('type', FavouriteType::TEAM_TYPE)
                    ->where('sports_type', FavouriteType::SPORTS_TYPE_FOOTBALL);
            }));

        $query->union($this->model()->select('fixture_id')
            ->join('users_favourites', 'favourite_id', '=', 'league_id')
            ->where('type_id', function (Builder $query) {
                $query->select('id')
                    ->from('user_favourite_type')
                    ->where('type', FavouriteType::LEAGUE_TYPE)
                    ->where('sports_type', FavouriteType::SPORTS_TYPE_FOOTBALL);
            }))
            ->where('users_favourites.user_id', $userId->toInt())
            ->where('date', $date->toCarbon()->toDateString());

        return $query->get()->pluck('fixture_id')->all();
    }

    private function model(): Model
    {
        return new class extends Model
        {
            protected $table = 'football_fixtures';
        };
    }
}
