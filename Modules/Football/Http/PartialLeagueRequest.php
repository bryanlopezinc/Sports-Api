<?php

declare(strict_types=1);

namespace Module\Football\Http;

use Illuminate\Http\Request;

/**
 * Input Filters to return only specific league fields in response
 */
final class PartialLeagueRequest
{
    /**
     * @param array<string> $requestedFields
     */
    public function __construct(private array $requestedFields)
    {
        $this->requestedFields = $this->transform(collect($requestedFields)->unique()->all());
    }

    private function transform(array $requestedAttributes): array
    {
        if (empty($requestedAttributes)) {
            return [];
        }

        $map = [
            // coverage types and there corresponding position in LeagueResource
            'coverage.stats'        => ['season.coverage.stats'],
            'coverage.events'       => ['season.coverage.events'],
            'coverage.line_up'      => ['season.coverage.line_up'],
            'coverage.top_scorers'  => ['season.coverage.top_scorers'],
            'coverage.top_assists'  => ['season.coverage.top_assists'],

            //Attributes to be push to requestedFields when season is requested
            //and their corresponding position in leagueResource
            'season' => [
                'season.season',
                'season.start',
                'season.end',
                'season.is_current_season',
            ],

            //Attributes to be push to requestedFields when season coverage is requested
            //and their corresponding position in leagueResource
            'coverage' => [
                'season.coverage.line_up',
                'season.coverage.events',
                'season.coverage.stats',
                'season.coverage.top_scorers',
                'season.coverage.top_assists',
            ],
        ];

        foreach ($map as $key => $value) {
            if (notInArray($key, $requestedAttributes)) {
                continue;
            }

            array_push($requestedAttributes, ...$value);

            //Remove the attribute after setting their corresponding positions
            //to avoid putting it at the wrong position in the resource
            unset($requestedAttributes[array_search($key, $requestedAttributes)]);
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
    public function all(array $except = []): array
    {
        return array_filter($this->requestedFields, fn (string $field) => notInArray($field, $except));
    }

    public function isEmpty(): bool
    {
        return empty($this->requestedFields);
    }

    public function has(string $field): bool
    {
        return inArray($field, $this->requestedFields);
    }
}
