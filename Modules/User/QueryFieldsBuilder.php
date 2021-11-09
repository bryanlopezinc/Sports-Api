<?php

declare(strict_types=1);

namespace Module\User;

use App\DTO\Builder;

final class QueryFieldsBuilder extends Builder
{
    public function id(): self
    {
        $this->attributes[] = QueryFields::ID;

        return $this;
    }

    public function favourites(): self
    {
        $this->attributes[] = QueryFields::FAVOURITES_COUNT;

        return $this;
    }

    public function isPrivate(): self
    {
        $this->attributes[] = QueryFields::IS_PRIVATE_PROFILE;

        return $this;
    }

    public function username(): self
    {
        $this->attributes[] = QueryFields::USERNAME;

        return $this;
    }

    public function email(): self
    {
        $this->attributes[] = QueryFields::EMAIL;

        return $this;
    }

    public function build(): QueryFields
    {
        return new QueryFields(
            array_unique($this->toArray())
        );
    }
}
