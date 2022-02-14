<?php

declare(strict_types=1);

namespace App\HashId;

use App\Exceptions\Http\ResourceNotFoundHttpException;
use Illuminate\Http\Request;

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

            $this->validateAttribute($request, $key);

            $decoded[$key] = $this->transform($request->input($key));
        }

        $request->merge($decoded);

        return $next($request);
    }

    private function validateAttribute(Request $request, string $key): void
    {
        $attribute = is_array($request->input($key)) ? "$key.*" : $key;

        $request->validate([$attribute => ['string']]);
    }

    /**
     * @throws ResourceNotFoundHttpException
     */
    public function transform(string|array $value): int|array
    {
        try {
            $result = array_map(fn (string $id) => $this->hashId->decode($id), (array) $value);

            return is_array($value) ? $result : $result[0];
        } catch (CannotDecodeHashIdException) {
            throw new ResourceNotFoundHttpException();
        }
    }

    public static function keys(): string
    {
        return 'convertId:' . implode(',', func_get_args());
    }
}
