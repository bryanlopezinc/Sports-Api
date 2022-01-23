<?php

declare(strict_types=1);

namespace Module\Football\Favourites;

use App\ValueObjects\ResourceId;
use App\ValueObjects\Uid;
use Illuminate\Support\Arr;
use Module\Football\Favourites\Exceptions\DuplicateEntryException;
use Module\Football\Favourites\Models\Favourite;
use Module\User\ValueObjects\UserId;
use Module\Football\ValueObjects\TeamId;
use Module\Football\ValueObjects\LeagueId;

final class Repository
{
    /**
     * @throws DuplicateEntryException
     */
    public function addTeam(TeamId $teamId, UserId $userId, Uid $recordId): bool
    {
        return $this->save($userId, $teamId, Favourite::TEAM_TYPE, $recordId);
    }

    /**
     * @throws DuplicateEntryException
     */
    public function addLeague(LeagueId $leagueId, UserId $userId, Uid $recordId): bool
    {
        return $this->save($userId, $leagueId, Favourite::LEAGUE_TYPE, $recordId);
    }

    private function save(UserId $userId, ResourceId $favouriteId, string $type, Uid $recordId): bool
    {
        $attributes = [
            'user_id'      => $userId->toInt(),
            'favourite_id' => $favouriteId->toInt(),
            'type'         => $type,
            'uid'          => $recordId->value
        ];

        $model = Favourite::query()->firstOrCreate(Arr::except($attributes, 'uid'), $attributes);

        if (!$model->wasRecentlyCreated) {
            throw new DuplicateEntryException();
        }

        return true;
    }
}
