<?php

declare(strict_types=1);

namespace Module\Football\Http;

use Illuminate\Http\Request;

/**
 * Input Filters to return only specific fixture players statistics fields in response
 */
final class PartialFixturePlayersStatisticsResourceRequest
{
    private readonly array $requestedFields;

    /**
     * @param array<string> $requestedFields
     */
    public function __construct(array $requestedFields)
    {
        //The player field is included by default
        $this->requestedFields = collect($requestedFields)->push('player')->unique()->all();
    }

    public static function fromRequest(Request $request, string $key = 'filter'): self
    {
        if (!$request->filled($key)) {
            return new self([]);
        }

        return new self($request->input($key));
    }

    /**
     * @return array<string>
     */
    public function all(): array
    {
        return $this->requestedFields;
    }

    public function isEmpty(): bool
    {
        return collect($this->requestedFields)->reject('player')->isEmpty();
    }
}
