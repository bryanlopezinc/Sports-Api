<?php

declare(strict_types=1);

namespace App\HashId;

use App\Exceptions\Http\ResourceNotFoundHttpException;

/**
 * Convert any hashed ids in request back to their original values.
 */
final class ConvertHashedValuesToIntegerMiddleware
{
    public function __construct(private HashIdInterface $hashId)
    {
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  array<string>  ...$keys The request keys to convert
     * @return mixed
     *
     * @throws ResourceNotFoundHttpException when a key cannot be converted
     */
    public function handle($request, \Closure $next, ...$keys)
    {
        $decoded = [];

        foreach ($keys as $key) {
            if (!$request->has($key)) {
                continue;
            }

            $decoded[$key] = $this->transform($request->input($key));
        }

        $request->merge($decoded);

        return $next($request);
    }

    /**
     * @throws ResourceNotFoundHttpException
     */
    public function transform(string $value): int
    {
        try {
            return $this->hashId->decode($value);
        } catch (CannotDecodeHashIdException) {
            throw new ResourceNotFoundHttpException();
        }
    }

    public static function keys(): string
    {
        return 'convertId:' . implode(',', func_get_args());
    }
}
