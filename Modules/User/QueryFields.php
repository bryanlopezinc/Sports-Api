<?php

declare(strict_types=1);

namespace Module\User;

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
    public const IS_PRIVATE_PROFILE  = 'profile_is_private';
    public const FAVOURITES_COUNT    = 'favourites_count';

    private const VALID = [
        self::ID,
        self::NAME,
        self::EMAIL,
        self::PASSWORD,
        self::USERNAME,
        self::IS_PRIVATE_PROFILE,
        self::FAVOURITES_COUNT
    ];

    /**
     * @param array<string> $fields
     */
    public function __construct(private array $fields = [])
    {
        $this->validateFields();
    }

    private function validateFields(): void
    {
        foreach ($this->fields as $field) {
            if (notInArray($field, self::VALID)) {
                throw new \InvalidArgumentException('Invalid field name ' . $field);
            }
        }
    }

    public function has(string $field): bool
    {
        return inArray($field, $this->fields);
    }

    /**
     * @return array<string>
     */
    public function all(): array
    {
        return $this->fields;
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
