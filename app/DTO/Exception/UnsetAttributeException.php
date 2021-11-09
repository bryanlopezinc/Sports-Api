<?php

declare(strict_types=1);

namespace App\DTO\Exception;

use Exception;

class UnsetAttributeException extends Exception
{
    public function __construct(string $attributeName, string $dtoClass)
    {
        parent::__construct('Cannot unset ' . $attributeName . ' attribute for ' . $dtoClass);
    }
}
