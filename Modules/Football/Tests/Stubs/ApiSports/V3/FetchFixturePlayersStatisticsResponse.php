<?php

declare(strict_types=1);

namespace Module\Football\Tests\Stubs\ApiSports\V3;

final class FetchFixturePlayersStatisticsResponse
{
    public static function json(): string
    {
        $DS = DIRECTORY_SEPARATOR;

        return file_get_contents(__DIR__ . $DS . 'json' . $DS . 'playersStats.json');
    }

    public static function noContent(): string
    {
        $decoded = json_decode(static::json(), true);

        $decoded['response'] = [];

        return json_encode($decoded);
    }
}
