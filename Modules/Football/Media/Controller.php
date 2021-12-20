<?php

declare(strict_types=1);

namespace Module\Football\Media;

use Symfony\Component\HttpFoundation\StreamedResponse;

final class Controller
{
    public function __construct(private FetchImage $fetchImage)
    {
    }

    public function coach(string $id): StreamedResponse
    {
        return $this->fetchImage->responseFor(FetchImage::COACH_ENDPOINT, $id);
    }

    public function leagueLogo(string $id): StreamedResponse
    {
        return $this->fetchImage->responseFor(FetchImage::LEAGUE_ENDPOINT, $id);
    }

    public function player(string $id): StreamedResponse
    {
        return $this->fetchImage->responseFor(FetchImage::PLAYER_ENDPOINT, $id);
    }

    public function teamLogo(string $id): StreamedResponse
    {
        return $this->fetchImage->responseFor(FetchImage::TEAM_ENDPOINT, $id);
    }
}
