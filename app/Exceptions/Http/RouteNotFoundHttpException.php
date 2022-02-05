<?php

declare(strict_types=1);

namespace App\Exceptions\Http;

use Symfony\Component\HttpKernel\Exception\HttpException;

final class RouteNotFoundHttpException extends HttpException
{
    public function __construct()
    {
        parent::__construct(404, 'Route not found');
    }
}
