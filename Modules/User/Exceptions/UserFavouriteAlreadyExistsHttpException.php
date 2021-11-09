<?php

declare(strict_types=1);

namespace Module\User\Exceptions;

use App\Exceptions\Http\HttpException;

final class UserFavouriteAlreadyExistsHttpException extends HttpException
{
    public function __construct()
    {
        parent::__construct(409, 'Cannot add resource to favorites because it already exists');
    }
}
