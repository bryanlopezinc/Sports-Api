<?php

declare(strict_types=1);

namespace App\Concerns;

use App\Utils\ValidateClassWithAtrributes;

trait ValidatesAfterCreating
{
    /**
     * Run class attributes (if any) to validate the object
     * after the object has been initialized
     */
    protected function runClassAfterMakingValidatorAttributes(): void
    {
        (new ValidateClassWithAtrributes($this))->runAfterMakingValidatorAttributes();
    }
}
