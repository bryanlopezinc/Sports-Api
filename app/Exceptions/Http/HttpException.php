<?php

declare(strict_types=1);

namespace App\Exceptions\Http;

use Symfony\Component\HttpKernel\Exception\HttpException as SymfonyHttpException;

class HttpException extends SymfonyHttpException
{
}
