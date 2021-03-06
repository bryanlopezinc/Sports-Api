<?php

declare(strict_types=1);

namespace Module\Football\Clients\ApiSports\V3;

use App\Utils\TimeToLive;
use Illuminate\Http\Client\Pool;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Request as HttpRequest;
use Module\Football\Clients\HttpRequestExceptionHandler;
use Psr\Http\Client\NetworkExceptionInterface;

abstract class ApiSportsClient
{
    public function __construct(private FailedResponseCache $cache = new FailedResponseCache)
    {
    }

    protected function get(string|ApiSportsRequest $url, array $query = []): Response
    {
        $request = $url instanceof ApiSportsRequest ? $url : new ApiSportsRequest($url, $query);

        return $this->pool(['response' => $request])['response'];
    }

    /**
     * @param array<string|int, ApiSportsRequest> $requests
     *
     * @return array<string, Response>
     */
    protected function pool(array $requests): array
    {
        $responses =  Http::pool(function (Pool $pool) use ($requests): array {
            $callback = function (ApiSportsRequest $request, string|int $alias) use ($pool) {
                return $pool
                    ->as((string) $alias)
                    ->withHeaders($request->headers())
                    ->beforeSending($this->checkForPreviousErrorResponse())
                    ->get($request->uri(), $request->query());
            };

            return collect($requests)->map($callback)->all();
        });

        $this->handleFailedErrorResponses($responses);

        return $responses;
    }

    private function checkForPreviousErrorResponse(): \Closure
    {
        return function (HttpRequest $request) {
            if ($this->cache->urlHasPreviousFailedResponse($request->url())) {
                HttpRequestExceptionHandler::handle($this->cache->getPreviousFailedResponseFor($request->url()));
            }
        };
    }

    /**
     * @param array<string, Response> $responses
     */
    private function handleFailedErrorResponses(array $responses): void
    {
        $determineTtlForStatusCode = fn (int $code): TimeToLive => match ($code) {
            404         => TimeToLive::seconds(120),
            default     => TimeToLive::seconds(60)
        };

        foreach ($responses as $response) {
            if ($response instanceof NetworkExceptionInterface) {
                abort(500);
            }

            $response->onError(fn (Response $response) => $this->cache->cache($response, $determineTtlForStatusCode($response->status())));
        }

        foreach ($responses as $response) {
            if ($response->failed()) {
                $response->onError(fn (Response $response) => HttpRequestExceptionHandler::handle($response));
            }
        }
    }
}
