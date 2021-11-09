<?php

declare(strict_types=1);

return [

    /*** The min season that is supported. */
    'minSeason' => 2008,

    /** Max age a finished fixture should be cached by the client */
    'finishedFixtureMaxAge' => 432_000,

    /** Max age a fixture in progress should be cached by the client */
    'FixtureInProgressMaxAge' => 60,

    /** Max age a find team response should be cached by the client */
    'findTeamResponseMaxAge' => 604_800,

    /** Max age a fetch team squad response should be cached by the client */
    'teamSquadResponseMaxAge' => 604_800,

    /** Max age a fetch league response should be cached by the client */
    'fetchLeagueResponseMaxAge' => 1800,

    /** Max age a fetch live fixtures response should be cached by the client */
    'fetchLiveFixturesResponseMaxAge' => 60,

    /** Cache Configurations */
    'cache' => require 'cache.php',
];
