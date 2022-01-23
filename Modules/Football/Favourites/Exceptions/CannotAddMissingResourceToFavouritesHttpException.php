<?php

declare(strict_types=1);

namespace Module\Football\Favourites\Exceptions;

use App\Exceptions\Http\HttpException;

final class CannotAddMissingResourceToFavouritesHttpException extends HttpException
{
    public function __construct()
    {
        parent::__construct(404, 'Cannot add resource to favorites because resource does not exists');
    }
}
