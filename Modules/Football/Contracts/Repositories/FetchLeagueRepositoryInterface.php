<?php

declare(strict_types=1);

namespace Module\Football\Contracts\Repositories;

use Module\Football\DTO\League;
use Module\Football\ValueObjects\Season;
use Module\Football\ValueObjects\LeagueId;
use Module\Football\Collections\LeaguesCollection;
use Module\Football\Collections\LeagueIdsCollection;

interface FetchLeagueRepositoryInterface
{
    /**
     * The full league data is returned in this response.
     *
     * @throws \App\Exceptions\Http\ResourceNotFoundHttpException
     */
    public function findByIdAndSeason(LeagueId $id, Season $season): League;

    /**
     * The full leagues data is returned in this response.
     * if There is a season in progress for each league, The league season data
     * equals the curent season in progress.
     * If the there is no season in progress for each league the league season data equals the
     * recently concluded league season.
     *
     * @throws \App\Exceptions\Http\ResourceNotFoundHttpException
     */
    public function findManyById(LeagueIdsCollection $ids): LeaguesCollection;
}
