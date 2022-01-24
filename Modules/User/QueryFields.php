<?php

declare(strict_types=1);

namespace Module\User;

use Illuminate\Support\Arr;
use ReflectionClass;

/**
 * The fields that should be returned from a user query
 */
final class QueryFields
{
    public const ID                  = 'id';
    public const NAME                = 'name';
    public const EMAIL               = 'email';
    public const PASSWORD            = 'password';
    public const USERNAME            = 'username';
    public const IS_PRIVATE_PROFILE  = 'is_private';
    public const FAVOURITES_COUNT    = 'favourites_count';

    /**
     * @param array<string> $fields
     */
    public function __construct(private array $fields = [])
    {
        $this->validateFields();
    }

    private function validateFields(): void
    {
        $valid = array_values((new ReflectionClass($this))->getConstants());

        foreach ($this->fields as $field) {
            if (notInArray($field, $valid)) {
                throw new \InvalidArgumentException('Invalid field name ' . $field);
            }
        }
    }

    public function has(string $field): bool
    {
        return inArray($field, $this->fields);
    }

    public function except(string|array $fields): array
    {
        return Arr::except($this->fields, $fields);
    }

    public function isEmpty(): bool
    {
        return empty($this->fields);
    }

    public static function builder(): QueryFieldsBuilder
    {
        return new QueryFieldsBuilder();
    }
}
