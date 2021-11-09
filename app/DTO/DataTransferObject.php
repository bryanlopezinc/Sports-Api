<?php

declare(strict_types=1);

namespace App\DTO;

use ArrayAccess;
use JsonSerializable;
use App\Concerns\ValidatesAfterCreating;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Contracts\Support\Arrayable;
use App\DTO\Exception\UnsetAttributeException;
use App\DTO\Exception\ChangeAttributeException;
use App\Attributes\CheckDataTransferObjectForDefaultProperties;

#[CheckDataTransferObjectForDefaultProperties]
abstract class DataTransferObject implements Jsonable, JsonSerializable, Arrayable, ArrayAccess
{
    use ValidatesAfterCreating;

    /**
     * @param array<string, mixed> $attributes
     */
    public function __construct(private array $attributes)
    {
        if (blank($attributes)) {
            return;
        }

        $this->setDtoAttributes();

        $this->runClassAfterMakingValidatorAttributes();
    }

    protected function setDtoAttributes(): void
    {
        foreach ($this->attributes as $key => $value) {
            $this->set($key, $value);
        }
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray()
    {
        return $this->attributes;
    }

    protected function set(string $key, mixed $value): void
    {
        if (property_exists($this, $key)) {
            $this->{$key} = ($value);
        }

        if (!$this->offsetExists($key)) {
            $this->attributes[$key] = $value;
        }
    }

    /**
     * @param  int  $options
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->jsonSerialize(), $options);
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }

    /**
     * @param  mixed  $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->attributes);
    }

    /**
     * @param  mixed  $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->offsetExists($offset) ? $this->attributes[$offset] : null;
    }

    /**
     * @param  mixed  $offset
     * @param  mixed  $value
     * @throws ChangeAttributeException
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        if ($this->offsetExists($offset)) {
            throw new ChangeAttributeException($offset, static::class);
        }

        $this->set($offset, $value);
    }

    /**
     * @param  mixed  $offset
     * @throws UnsetAttributeException
     * @return void
     */
    public function offsetUnset($offset)
    {
        throw new UnsetAttributeException($offset, static::class);
    }

    /**
     * @param  string  $key
     * @param  mixed  $value
     * @return void
     */
    public function __set($key, $value)
    {
        $this->offsetSet($key, $value);
    }
}
