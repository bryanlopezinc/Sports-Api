<?php

declare(strict_types=1);

namespace Module\Football\Routes;

final class RouteName
{
    public const FIND_TEAM                   = 'soccer:findTeam';
    public const TEAMS_H2H                   = 'soccer:teamsHead2Head';
    public const TEAM_SQUAD                  = 'soccer:teamSquad';
    public const FIND_LEAGUE                 = 'soccer:findleague';
    public const LEAGUE_STANDING             = 'soccer:leagueTable';
    public const LEAGUE_TOP_SCORERS          = 'soccer:leagueTopScoreres';
    public const LEAGUE_TOP_ASSISTS          = 'soccer:leagueTopAssists';
    public const FIND_FIXTURE                = 'soccer:findfixture';
    public const FIXTURE_LINEUP              = 'soccer:fixtureLineup';
    public const FIXTURE_STATS               = 'soccer:fixtureStatistics';
    public const LEAGUE_FIXTURE_BY_DATE      = 'soccer:leagueFixturesByDate';
    public const LIVE_FIXTURES               = 'soccer:liveFixtures';
    public const FIXTURES_BY_DATE            = 'soccer:fixtureByDate';
    public const FIXTURE_PLAYERS_STAT        = 'soccer:fixturePlayersStatistics';
    public const FIXTURE_EVENTS              = 'soccer:fixtureEvents';
    public const FIXTURE_PREDICTIONS         = 'soccer:fixturePredictions';
    public const FIND_COACH                  = 'soccer:findCoach';
    public const COACH_CAREER_HISTORY        = 'soccer:coachCareerHistory';
    public const FIND_PLAYER                 = 'soccer:findPlayer';
    public const PLAYER_TRANSFER_HISTORY     = 'soccer:playerTransferHistory';
    public const PREDICT_FIXTURE             = 'soccer:predictFixture';
    public const ADD_TEAM_TO_FAVOURITES      = 'soccer:AddTeamToFavourites';
    public const ADD_LEAGUE_TO_FAVOURITES    = 'soccer:AddLeagueToFavourites';
}
