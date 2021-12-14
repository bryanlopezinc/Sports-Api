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
        $this->requestedFields = collect($requestedFields)->unique()->all();

        $this->normalizeRequestData();
    }

    private function normalizeRequestData(): void
    {
        $attributes = $this->requestedFields;

        //Ignore period_goals if specific period_goals data is requested
        if ($this->wantsSpecificPeriodGoalsData() && $this->wants('period_goals')) {
            unset($attributes[array_search('period_goals', $attributes)]);
        }

        $this->requestedFields = $attributes;
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

    /**
     * Determine if any of the given attributes is requested.
     */
    public function wantsAnyOf(array $attributes): bool
    {
        foreach ($attributes as $attribute) {
            if (inArray($attribute, $this->requestedFields)) {
                return true;
            }
        }

        return false;
    }

    public function wants(string $field): bool
    {
        return $this->wantsAnyOf([$field]);
    }

    /**
     * Return true if any of period_goals first_half, second_half, extra_time, penalty data is requested
     */
    public function wantsSpecificPeriodGoalsData(): bool
    {
        return !empty($this->getPeriodGoalsTypes());
    }

    /**
     * Return any of period_goals first_half, second_half, extra_time, penalty data if requested
     *
     * @return array<string>
     */
    public function getPeriodGoalsTypes(): array
    {
        return collect($this->requestedFields)
            ->filter(fn (string $field): bool => str_starts_with($field, 'period_goals.'))
            ->map(fn (string $field): string => str_replace('period_goals.', '', $field))
            ->all();
    }
}
