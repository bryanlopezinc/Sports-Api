<?php

declare(strict_types=1);

namespace Module\User\Routes;

final class RouteName
{
    public const ADD_FOOTBALL_LEAGUE_TO_FAVOURITES  = 'user.addLeagueToFavourites';
    public const ADD_FOOTBALL_TEAM_TO_FAVOURITES    = 'user.addFootballTeamToFavourites';
    public const AUTH_USER_FAVOURITES               = 'auth.user.favourites';
    public const AUTH_USER_PROFILE                  = 'auth.user.profile';
    public const FAVOURITES                         = 'user.favourites';
    public const PROFILE                            = 'user.profile';
    public const LOGIN                              = 'user.login';
    public const CREATE                             = 'user.create';
}
