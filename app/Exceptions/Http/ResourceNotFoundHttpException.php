<?php

declare(strict_types=1);

namespace App\Exceptions\Http;

use Symfony\Component\HttpKernel\Exception\HttpException;

final class ResourceNotFoundHttpException extends HttpException
{
    public function __construct()
    {
        parent::__construct(404, 'Resource not found');
    }
}
