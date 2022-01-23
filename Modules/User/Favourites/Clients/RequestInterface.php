<?php

declare(strict_types=1);

namespace Module\User\Favourites\Clients;

use Illuminate\Http\Client\Response;
use Illuminate\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;

interface RequestInterface
{
    /**
     * Each key in request is used as a response key in the responses array.
     *
     * @param Paginator<Model> $collection
     *
     * @return array<string, Request>
     */
    public function buildRequestObjectsWith(Paginator $collection): array;

    /**
     * @param array<string, Response> $response
     */
    public function mapResponsesToDto(array $response): array;
}
