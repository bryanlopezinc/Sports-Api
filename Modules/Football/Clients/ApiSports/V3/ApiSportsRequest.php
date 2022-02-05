<?php

declare(strict_types=1);

namespace Module\Football\Clients\ApiSports\V3;

use Stringable;
use App\Utils\Config;
use Module\Football\ValueObjects\LeagueId;
use Module\Football\ValueObjects\TeamId;

class ApiSportsRequest
{
    protected string $uri;

    /**
     * @param array<string, string> $headers
     * @param array<string, mixed> $query
     */
    public function __construct(protected string|Stringable $route, protected array $query = [])
    {
        $this->uri = 'https://v3.football.api-sports.io/' . $route;
    }

    public static function findLeagueRequest(LeagueId $id, array $query = []): self
    {
        return new self('leagues', array_merge(['id' => $id->toInt()], $query));
    }

    public static function findTeamRequest(TeamId $id): self
    {
        return new self('teams', ['id' => $id->toInt()]);
    }

    /**
     * @return array<string, mixed>
     */
    public function query(): array
    {
        return $this->query;
    }

    /**
     * @return array<string, mixed>
     */
    public function headers(): array
    {
        return [
            'x-rapidapi-key' => Config::get('services.apisports.token')
        ];
    }

    public function uri(): string
    {
        return $this->uri;
    }
}
