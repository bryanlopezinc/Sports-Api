<?php

declare(strict_types=1);

namespace Module\Football\Clients;

use Illuminate\Http\Client\Response;
use App\Exceptions\Http\HttpException;
use App\Exceptions\Http\ResourceNotFoundHttpException;

final class HttpRequestExceptionHandler
{
    public function __construct(private Response $response)
    {
    }

    public static function handle(Response $response): void
    {
        (new static($response))->throwException();
    }

    protected function throwException(): void
    {
        if (!$this->response->failed()) {
            throw new \InvalidArgumentException('cannot handle successful api call with code ' . $this->response->status());
        }

        $exception = match ($this->response->status()) {
            404     => throw new ResourceNotFoundHttpException,
            500     => $this->getException(500, 'Something went wrong while fetching details. Try again later'),
            499     => $this->getException(499, 'Something went wrong while fetching details. Try again later'),
            429     => $this->getException(503, 'Service Unavailable'),
            default => new HttpException(500)
        };

        throw $exception;
    }

    private function getException(int $code, string $message): HttpException
    {
        return new class($code, $message) extends HttpException
        {
            public function __construct(int $code, string $message)
            {
                parent::__construct($code, $message);
            }
        };
    }
}
