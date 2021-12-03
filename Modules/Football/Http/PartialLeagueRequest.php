<?php

declare(strict_types=1);

namespace Module\Football\Http;

use Illuminate\Http\Request;
use Module\Football\Exceptions\Http\InvalidPartialResourceFieldsHttpException;

/**
 * Input Filters to return only specific league fields in response
 */
final class PartialLeagueRequest
{
    private const ALLOWED = [
        'logo_url',
        'name',
        'country',
        'season',
        'season.season',
        'season.start',
        'season.end',
        'season.is_current_season',
        'coverage',
        'coverage.line_up',
        'coverage.events',
        'coverage.stats',
        'coverage.top_scorers',
        'coverage.top_assists',
        'links',
        'id'
    ];

    /**
     * @param array<string> $requestedFields
     */
    public function __construct(private array $requestedFields)
    {
        $this->requestedFields = collect($requestedFields)->unique()->all();

        $this->validate();

        $this->normalizeRequestData();
    }

    private function validate(): void
    {
        // Only id cannot be requested
        if (count($this->requestedFields) === 1 && inArray('id', $this->requestedFields)) {
            throw new InvalidPartialResourceFieldsHttpException('Only id cannot be requested');
        }

        foreach ($this->requestedFields as $field) {
            if (!inArray($field, self::ALLOWED)) {
                throw new InvalidPartialResourceFieldsHttpException();
            }
        }
    }

    private function normalizeRequestData(): void
    {
        $attributes = $this->requestedFields;

        //Ignore coverage if specific coverage data is requested
        if ($this->wantsSpecificCoverageData() && $this->wants('coverage')) {
            unset($attributes[array_search('coverage', $attributes)]);
        }

        //Ignore season if specific season data is requested
        if ($this->wantsSpecificSeasonData() && $this->wants('season')) {
            unset($attributes[array_search('season', $attributes)]);
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
     * Return true if any of : coverage line_up, events, stats, top_scorers or top_assists data is requested
     */
    public function wantsSpecificCoverageData(): bool
    {
        return !empty($this->getCoverageTypes());
    }

    /**
     * Return any of : coverage line_up, events, stats, top_scorers or top_assists data if requested
     *
     * @return array<string>
     */
    public function getCoverageTypes(): array
    {
        return collect($this->requestedFields)
            ->filter(fn (string $field): bool => str_starts_with($field, 'coverage.'))
            ->map(fn (string $field): string => str_replace('coverage.', '', $field))
            ->all();
    }

    /**
     * Return true if any of season(year), start, end or is_current_season is requested
     */
    public function wantsSpecificSeasonData(): bool
    {
        return !empty($this->getSeasonTypes());
    }

    /**
     * Returns any of season(year), start, end or is_current_season if requested
     *
     * @return array<string>
     */
    public function getSeasonTypes(): array
    {
        return collect($this->requestedFields)
            ->filter(fn (string $field): bool => str_starts_with($field, 'season.'))
            ->map(fn (string $field): string => str_replace('season.', '', $field))
            ->all();
    }
}
