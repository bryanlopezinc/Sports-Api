<?php

declare(strict_types=1);

namespace App\Utils;

use ReflectionClass;
use ReflectionAttribute;
use App\Contracts\AfterMakingValidatorInterface;

/**
 * Validate a clas using its defined attributes and parent attributes
 */
final class ValidateClassWithAtrributes
{
    /**
     * Instances of initialized attributes.
     *
     * Structure {
     *  path/to/class => array<AfterMakingValidatorInterface>
     * }
     *
     * @var array<string, array<AfterMakingValidatorInterface>>
     */
    private static array $cache = [];

    public function __construct(private Object $object)
    {
        $this->cacheClassAttributes();
    }

    private function cacheClassAttributes(): void
    {
        if (array_key_exists($this->object::class, static::$cache)) {
            return;
        }

        static::$cache[$this->object::class] = $this->getClassAttributesInstances();
    }

    public function runAfterMakingValidatorAttributes(): void
    {
        foreach (static::$cache[$this->object::class] as $validator) {
            $validator->validate($this->object);
        }
    }

    /**
     * @return array<AfterMakingValidatorInterface>
     */
    private function getClassAttributesInstances(): array
    {
        $attrbutes = collect();

        $reflection = new ReflectionClass($this->object);

        while ($reflection !== false) {

            $attrbutes->push(...$reflection->getAttributes(AfterMakingValidatorInterface::class, ReflectionAttribute::IS_INSTANCEOF));

            $reflection = $reflection->getParentClass();
        }

        return $attrbutes
            ->reject(fn (?ReflectionAttribute $a): bool => blank($a))
            ->map(fn (ReflectionAttribute $a): AfterMakingValidatorInterface => $a->newInstance())
            ->all();
    }
}
