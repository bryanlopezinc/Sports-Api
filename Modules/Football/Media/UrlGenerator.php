<?php

declare(strict_types=1);

namespace Module\Football\Media;

use App\ValueObjects\ResourceId;
use Module\Football\ValueObjects\TeamId;
use Module\Football\ValueObjects\CoachId;
use Module\Football\ValueObjects\LeagueId;
use Module\Football\ValueObjects\PlayerId;

final class UrlGenerator
{
    public static function new(): self
    {
        return new self;
    }

    public function leagueLogo(LeagueId $id): ImageUrl
    {
        return $this->generateUrl('league.logo', $id);
    }

    public function coachPhoto(CoachId $id): ImageUrl
    {
        return $this->generateUrl('coach.photo', $id);
    }

    public function playerPhoto(PlayerId $id): ImageUrl
    {
        return $this->generateUrl('player.photo', $id);
    }

    public function teamLogo(TeamId $id): ImageUrl
    {
        return $this->generateUrl('team.logo', $id);
    }

    private function generateUrl(string $routeName, ResourceId $id): ImageUrl
    {
        return new ImageUrl(route($routeName, [$id->asHashedId()]));
    }
}
