<?php

declare(strict_types=1);

namespace Module\User\Favourites\Clients;

final class Request
{
    /**
     * @param array<mixed> $query
     * @param array<string, mixed> $headers
     */
    public function __construct(
        private string $uri,
        private array $query = [],
        private array $headers = []
    ) {
    }

    /**
     * @return array<mixed>
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
        return $this->headers;
    }

    public function uri(): string
    {
        return $this->uri;
    }
}
