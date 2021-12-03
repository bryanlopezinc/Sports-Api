<?php

declare(strict_types=1);

namespace Module\Football\Exceptions\Http;

use App\Exceptions\Http\HttpException;

final class InvalidPartialResourceFieldsHttpException extends HttpException
{
    public function __construct(string $mesaage = null)
     {
         parent::__construct(400, $mesaage ?: 'The given partial resource fields are Invalid');
     }
}