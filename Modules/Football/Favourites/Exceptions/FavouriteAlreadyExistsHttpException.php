<?php

declare(strict_types=1);

namespace Module\Football\Favourites\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpFoundation\Response;

final class FavouriteAlreadyExistsHttpException extends HttpException
{
    public function __construct()
    {
        parent::__construct(Response::HTTP_CONFLICT, 'Cannot add resource to favorites because it already exists');
    }
}
