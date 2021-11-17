<?php

declare(strict_types=1);

namespace Module\Football\Routes;

final class Name
{
    public const FETCH_TEAM                        = 'team.find';
    public const FETCH_TEAM_SQUAD                  = 'team.squad';
    public const FETCH_TEAM_FXTURES                = 'team.fixtures';
    public const FETCH_TEAM_HEAD_TO_HEAD           = 'team.head2head';
    public const FETCH_LEAGUE                      = 'league.find';
    public const FETCH_LEAGUE_STANDING             = 'league.table';
    public const FETCH_LEAGUE_TOP_SCORERS          = 'league.topScoreres';
    public const FETCH_LEAGUE_TOP_ASSISTS          = 'league.topAssists';
    public const FETCH_FIXTURE                     = 'fixture.find';
    public const FETCH_FIXTURE_LINEUP              = 'fixture.lineup';
    public const FETCH_FIXTURE_STATS               = 'fixture.statistics';
    public const FETCH_LEAGUE_FIXTURE_BY_DATE      = 'fixture.leagues.byDate';
    public const FETCH_LIVE_FIXTURES               = 'fixtures.live';
    public const FETCH_FIXTURES_BY_DATE            = 'fixtures.by_date';
    public const FETCH_FIXTURE_EVENTS              = 'fixture.events';
    public const FETCH_COACH                       = 'coach.find';
    public const FETCH_COACH_CAREER_HISTORY        = 'coach.careerHistory';
}
