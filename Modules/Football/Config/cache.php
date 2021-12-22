<?php

declare(strict_types=1);

return [

    'teams' => [
        'ttl'       => 1 //in days
    ],

    'teamsSquad' => [
        'ttl'       => 7 //in days
    ],

    'leagues' => [
        'ttl'       => 1 //in days
    ],

    'leaguesSeasons' => [
        'ttl'       => 3600 //in seconds
    ],

    'leaguesFixturesByDate' => [
        'defaultTtl'    => 30 //Default ttl (in minutes) a league fixtures by date should be cached
    ],

    'coaches' => [
        'defaultTtl'    => 7 //Default ttl (in days) a coach should be cached
    ],

    'coachesCareers' => [
        'defaultTtl'    => 5 //Default ttl (in days) a coach career should be cached
    ],

    'leaguesStandings' => [
        //ttl in hours a league table should be cache when its not for current season
        'ttlWhenNotCurrentSeason'   => 2,

        //ttl in minutes a league table should be cache when league has fixture in progress
        'ttlWhenHasFixtureInProgress' => 10
    ],

    'leaguesTopScorers' => [
        'defaultTtl'    => 180 //Default ttl (in minutes) a league top scorers should be cached
    ],

    'leaguesTopAssists' => [
        'defaultTtl'    => 180 //Default ttl (in minutes) a league top assists should be cached
    ],

    'fixtures' => [
        'ttl'       => [
            // Ttl in days a Finished fixtures should be stored in cache
            'finished'  => 2,

            // Ttl in seconds a fixture in progress should be stored in cache
            'inProgress' => 60,

            // Ttl in seconds a fixture which time is yet to be defined should be stored in cache
            'whenIsTBD' => 600,

            // Ttl in secinds a fixture that is suspended should be stored in cache
            'suspended' => 600,

            // Ttl in minutes a fixture that is postponed should be stored in cache
            'postponed' => 10,

            // Ttl in minutes a fixture that is cacncelled should be stored in cache
            'cancelled' => 10,

            // Ttl in seconds a fixture that is abandoned should be stored in cache
            'abandoned' => 600,
        ]
    ],

    'fixturesStatistics' => [
        'ttl'       => [
            // Ttl in seconds a Finished fixture statistics should be stored in cache */
            'finished'  => 86_400,

            // Ttl in seconds to cache a fixture statistics when fixture is in progress */
            'inProgress' => 60,
        ]
    ],

    'teamH2H' => [
        'ttl'       => [

            // Ttl in seconds to cache team head to head when fixture is in progress */
            'inProgress' => 120,
        ]
    ],
];
