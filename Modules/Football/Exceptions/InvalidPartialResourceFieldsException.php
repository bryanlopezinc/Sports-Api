<?php

declare(strict_types=1);

namespace Module\Football\Exceptions;

final class InvalidPartialResourceFieldsException extends \Exception
{
    public function __construct(string $message, $code = 0, \Throwable $previous = null)
     {
         parent::__construct($message, $code, $previous);
     }
}