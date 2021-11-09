<?php

declare(strict_types=1);

namespace Module\Football\Tests\Stubs\ApiSports\V3;

final class FetchFixtureStatisticsResponse
{
    public static function json(): string
    {
        $DS = DIRECTORY_SEPARATOR;

        return file_get_contents(__DIR__ . $DS . 'json' . $DS . 'fixtureStats.json');
    }
}
