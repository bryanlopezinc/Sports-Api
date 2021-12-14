<?php

declare(strict_types=1);

namespace Module\User\Favourites\Exceptions;

use App\Exceptions\Http\HttpException;

/**
 * Exception thrown when tryin to add a resource that does not exists to favorites
 */
final class CannotAddMissingResourceToFavouritesHttpException extends HttpException
{
    public function __construct()
    {
        parent::__construct(404, 'Cannot add resource to favorites because resource does not exists');
    }
}
