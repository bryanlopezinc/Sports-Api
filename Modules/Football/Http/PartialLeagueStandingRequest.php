<?php

declare(strict_types=1);

namespace Module\Football\Http;

use Illuminate\Http\Request;
use Module\Football\Exceptions\Http\InvalidPartialResourceFieldsHttpException;

/**
 * Input Filters to return specific league standing fields in response.
 *
 * The TEAM field is included in all requests by default for any partial resource to make sense.
 */
final class PartialLeagueStandingRequest
{
    /**
     * @param array<string> $requestedFields
     */
    public function __construct(private array $requestedFields)
    {
        if (empty($requestedFields)) {
            return;
        }

        $this->requestedFields = collect($requestedFields)->values()->unique()->all();

        $this->normalizeRequestData();
    }

    private function normalizeRequestData(): void
    {
        if (notInArray('team', $this->requestedFields)) {
            $this->requestedFields[] = 'team';
        }
    }

    public static function fromRequest(Request $request, string $key = 'filter'): self
    {
        if (!$request->filled($key)) {
            return new self([]);
        }

        return new self(explode(',', $request->input($key)));
    }

    /**
     * @return array<string>
     */
    public function all(): array
    {
        return $this->requestedFields;
    }

    public function wantsPartialResponse(): bool
    {
        return !empty($this->requestedFields);
    }

    public function wants(string $field): bool
    {
        return inArray($field, $this->requestedFields);
    }
}
