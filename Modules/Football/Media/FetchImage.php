<?php

declare(strict_types=1);

namespace Module\Football\Media;

use App\HashId\HashIdInterface;
use App\HashId\CannotDecodeHashIdException;
use App\Exceptions\Http\ResourceNotFoundHttpException;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class FetchImage
{
    private const EXT = 'png';

    public const COACH_ENDPOINT  = 'https://media.api-sports.io/football/coachs/';
    public const LEAGUE_ENDPOINT = 'https://media.api-sports.io/football/leagues/';
    public const PLAYER_ENDPOINT = 'https://media.api-sports.io/football/players/';
    public const TEAM_ENDPOINT   = 'https://media.api-sports.io/football/teams/';

    public function __construct(
        private HashIdInterface $hashIdInterface,
        private FetchImageHttpClientInterface $client
    ) {
    }

    /**
     * @throws ResourceNotFoundHttpException
     */
    public function responseFor(string $endpoint, string $id): StreamedResponse
    {
        $streamCallback = function () use ($endpoint, $id) {
            $url = $endpoint . $this->decodeId($id) . '.' . self::EXT;

            echo $this->client->response($url);
        };

        return response()->stream($streamCallback, headers: ['Content-type' => 'image/' . self::EXT]);
    }

    private function decodeId(string $id): int
    {
        try {
            return $this->hashIdInterface->decode($id);
        } catch (CannotDecodeHashIdException) {
            throw new ResourceNotFoundHttpException;
        }
    }
}
