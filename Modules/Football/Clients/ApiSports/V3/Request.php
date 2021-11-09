<?php

declare(strict_types=1);

namespace Module\Football\Clients\ApiSports\V3;

use Stringable;
use App\Utils\Config;

class Request
{
    protected string $uri;

    /**
     * @param array<string, string> $headers
     * @param array<string, mixed> $query
     */
    public function __construct(protected string|Stringable $route, protected array $query = [], protected array $headers = [])
    {
        $this->uri = 'https://v3.football.api-sports.io/' . $route;
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
        return array_merge($this->headers, [
            'x-rapidapi-key' => Config::get('services.apisports.token')
        ]);
    }

    public function uri(): string
    {
        return $this->uri;
    }
}
