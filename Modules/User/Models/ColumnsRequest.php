<?php

declare(strict_types=1);

namespace Module\User\Models;

use Module\User\QueryFields;
use Module\User\Database\Column;
use Illuminate\Database\Eloquent\Model;

final class ColumnsRequest
{
    private const MAP = [
        QueryFields::ID                   => Column::ID,
        QueryFields::NAME                 => Column::NAME,
        QueryFields::EMAIL                => Column::EMAIL,
        QueryFields::PASSWORD             => Column::PASSWORD,
        QueryFields::USERNAME             => Column::USERNAME,
        QueryFields::IS_PRIVATE_PROFILE   => Column::IS_PRIVATE,
        QueryFields::FAVOURITES_COUNT     => Column::FAVOURITES_COUNT,
    ];

    private const RELATIONS = [
        Column::FAVOURITES_COUNT
    ];

    /**
     * @var array<string>
     */
    private array $requestedColumns;

    public function __construct(private QueryFields $fields)
    {
        $this->requestedColumns = $this->convertQueryFieldsToDbColumns();
    }

    /**
     * @return array<string>
     */
    private function convertQueryFieldsToDbColumns(): array
    {
        return array_map(fn (string $field): string => self::MAP[$field], $this->fields->all());
    }

    /**
     * @return array<string>
     */
    public function allExceptRelations(): array
    {
        return collect($this->requestedColumns)->reject(fn (string $column): bool => inArray($column, self::RELATIONS))->all();
    }

    /**
     * @return array<string>
     */
    public function qualifyAllExceptRelationsWith(Model $model): array
    {
        return array_map(fn (string $column): string => $model->qualifyColumn($column), $this->allExceptRelations());
    }

    public function wantsFavouritesCountRelation(): bool
    {
        return inArray(Column::FAVOURITES_COUNT, $this->requestedColumns);
    }

    /**
     * @return array<string>
     */
    public function converted(): array
    {
        return $this->requestedColumns;
    }

    public function gueryFields(): QueryFields
    {
        return $this->fields;
    }
}
