<?php

declare(strict_types=1);

namespace Module\User\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;

final class PrivateUserProfileHttpException extends HttpException
{
    public function __construct()
    {
        parent::__construct(403, 'Cannot view user resource because profile is marked as private');
    }
}
