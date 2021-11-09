<?php

declare(strict_types=1);

namespace App\Contracts;

interface AfterMakingValidatorInterface
{
    public function validate(Object $object): void;
}
