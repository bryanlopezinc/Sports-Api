<?php

declare(strict_types=1);

namespace App\DTO\Exception;

use Exception;

class PropertyCannotHaveDefaultValueException extends Exception
{
    public function __construct(string $className)
    {
        parent::__construct(
            sprintf(
                '%s property/ies cannot have a default values ',
                $className,
            )
        );
    }
}
