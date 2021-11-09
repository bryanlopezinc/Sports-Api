<?php

declare(strict_types=1);

namespace App\Rules;

use App\ValueObjects\ResourceId;
use Illuminate\Contracts\Validation\Rule;
use App\Exceptions\InvalidResourceIdException;

final class ResourceIdRule implements Rule
{
    protected string|array $message;

    /**
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        try {
            new class((int) $value) extends ResourceId
            {
            };

            return true;
        } catch (InvalidResourceIdException) { // @phpstan-ignore-line

            $this->message = 'invalid resource id';

            return false;
        }
    }

    /**
     * @return string|array
     */
    public function message()
    {
        return $this->message;
    }
}
