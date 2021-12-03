<?php

declare(strict_types=1);

namespace Module\Football\Http;

use Illuminate\Http\Request;
use Module\Football\Exceptions\Http\InvalidPartialResourceFieldsHttpException;

/**
 * Input Filters to return specific league standing fields in response.
 *
 * The TEAM field is included in all requests by default for any partial resource to make sense. However,
 * Only the TEAM field cannot be requested
 */
final class PartialLeagueStandingRequest
{
    private const ALLOWED = [
        'points',
        'position',
        'team',
        'team_form',
        'played',
        'won',
        'lost',
        'draws',
        'home_record',
        'away_record',
        'goal_difference',
        'goals_found',
        'goals_against',
        'league'
    ];

    /**
     * @param array<string> $requestedFields
     */
    public function __construct(private array $requestedFields)
    {
        if (empty($requestedFields)) {
            return;
        }

        $this->requestedFields = collect($requestedFields)->values()->unique()->all();

        $this->validate();

        $this->normalizeRequestData();
    }

    private function validate(): void
    {
        foreach ($this->requestedFields as $field) {
            if (notInArray($field, self::ALLOWED)) {
                throw new InvalidPartialResourceFieldsHttpException();
            }
        }

        if (count($this->requestedFields) === 1 && $this->requestedFields[0] === 'team') {
            throw new InvalidPartialResourceFieldsHttpException('Only team field cannot be requested');
        }
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
