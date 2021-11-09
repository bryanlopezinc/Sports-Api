<?php

declare(strict_types=1);

namespace App\Exceptions;

class InvalidCollectionItemException extends \Exception
{
    public function __construct(int|string $index, string $class, mixed $item)
    {
        parent::__construct(
            sprintf(
                'Invalid collection item [%s] for %s at index %s',
                is_object($item) ? $item::class : gettype($item),
                $class,
                $index
            )
        );
    }
}
