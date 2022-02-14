<?php

declare(strict_types=1);

namespace Module\Football\Http;

use Illuminate\Http\Request;

/**
 * Input Filters to return only specific fixture fields in response
 */
final class PartialFixtureRequest
{
    /**
     * @param array<string> $requestedFields
     */
    public function __construct(private array $requestedFields)
    {
        $this->requestedFields = $this->normalizeRequestData(collect($requestedFields)->unique()->all());
    }

    private function normalizeRequestData(array $requestedAttributes): array
    {
        if (empty($requestedAttributes)) {
            return [];
        }

        $extraAttributesMap = [
            'venue'   => ['has_venue_info'],
            'winner'  => ['has_winner'],
            'score'   => ['score_is_available'],
            'period_goals.first_half'  => ['period_goals.meta.has_first_half_score'],
            'period_goals.second_half' => ['period_goals.meta.has_full_time_score'],
            'period_goals.extra_time'  => ['period_goals.meta.has_extra_time_score'],
            'period_goals.penalty'     => ['period_goals.meta.has_penalty_score']
        ];

        foreach ($extraAttributesMap as $name => $extraAttributes) {
            if (notInArray($name, $requestedAttributes)) {
                continue;
            }

            array_push($requestedAttributes, ...$extraAttributes);
        }

        return array_values($requestedAttributes);
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
        return empty($this->requestedFields);
    }

    public function wants(string $field): bool
    {
        return inArray($field, $this->requestedFields);
    }
}
