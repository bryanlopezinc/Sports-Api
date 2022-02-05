<?php

declare(strict_types=1);

namespace Module\User\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;

final class UserNotFoundHttpException extends HttpException
{
    public function __construct()
    {
        parent::__construct(404, 'User Not Found');
    }
}
