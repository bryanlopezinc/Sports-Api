<?php

declare(strict_types=1);

namespace App\DTO\Exception;

use Exception;

class ChangeAttributeException extends Exception
{
    public function __construct(string $attributeName, string $dtoClass)
    {
        parent::__construct(
            sprintf(
                'Cannot change %s attribute for %s',
                $attributeName,
                $dtoClass
            )
        );
    }
}
