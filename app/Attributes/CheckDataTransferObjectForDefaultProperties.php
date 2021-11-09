<?php

declare(strict_types=1);

namespace App\Attributes;

use Attribute;
use ReflectionClass;
use ReflectionProperty;
use App\Contracts\AfterMakingValidatorInterface;
use App\DTO\Exception\PropertyCannotHaveDefaultValueException;

#[Attribute(Attribute::TARGET_CLASS)]
final class CheckDataTransferObjectForDefaultProperties implements AfterMakingValidatorInterface
{
    /**
     * @var array<string, bool>
     */
    private static array $checked = [];

    private ReflectionClass $reflection;

    public function validate(Object $object): void
    {
        if (array_key_exists($object::class, static::$checked)) {
            return;
        }

        $this->reflection = new ReflectionClass($object);

        $this->ensureNoPropertyHasDefaultValue();

        static::$checked[$object::class] = true;
    }

    protected function ensureNoPropertyHasDefaultValue(): void
    {
        $objectHasPropertyWithDefaultValue = collect($this->reflection->getProperties())
            ->filter(fn (ReflectionProperty $property): bool => $property->hasDefaultValue())
            ->isNotEmpty();

        if ($objectHasPropertyWithDefaultValue) {
            throw new PropertyCannotHaveDefaultValueException($this->reflection->getName());
        }
    }
}
