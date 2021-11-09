<?php

declare(strict_types=1);

namespace Module\User\Http\Controllers;

use Illuminate\Http\Response;
use Psr\Http\Message\ServerRequestInterface;
use Laravel\Passport\Http\Controllers\AccessTokenController;

final class LoginController extends AccessTokenController
{
    /**
     * @return Response
     */
    public function __invoke(ServerRequestInterface $request)
     {
         return $this->issueToken($request);
     }
}