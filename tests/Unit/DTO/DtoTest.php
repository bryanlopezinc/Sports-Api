<?php

declare(strict_types=1);

namespace Tests\Unit\DTO;

use Tests\TestCase;
use App\DTO\DataTransferObject;
use App\DTO\Exception\UnsetAttributeException;
use App\DTO\Exception\ChangeAttributeException;
use App\DTO\Exception\PropertyCannotHaveDefaultValueException;

class DtoTest extends TestCase
{
    /**
     * @return array<mixed>
     */
    public function dtoData()
    {
        return [
            [
                [
                    'foo'    => 'scratched 3 million dollars off my checkList 3 years ago',
                    'baz'    => 'am tip toeing to the bank hope u dont speak to me',
                    'foobar' => 'bar_foo',
                    'bafoo'  => 'out_of_foos'
                ]
            ]
        ];
    }

    /**
     * @dataProvider dtoData
     */
    public function test_support_readonly_properties(array $data): void
    {
        $class = new class($data) extends DataTransferObject
        {
            public readonly string $foo;
            public readonly string $baz;
            public readonly string $foobar;
            public readonly string $bafoo;
        };

        $this->assertEquals($class->foo, 'scratched 3 million dollars off my checkList 3 years ago');
        $this->assertEquals($class->baz, 'am tip toeing to the bank hope u dont speak to me');
        $this->assertEquals($class->foobar, 'bar_foo');
        $this->assertEquals($class->bafoo, 'out_of_foos');
    }

    public function test_is_empty(): void
    {
        $class = new class([]) extends DataTransferObject
        {
            protected string $foo;
            protected string $baz;
            protected string $foobar;
            protected string $bafoo;
        };

        $this->assertTrue($class->isEmpty());
    }

    /**
     * @dataProvider dtoData
     */
    public function test_will_set_defined_properties(array $data): void
    {
        $class = new class($data) extends DataTransferObject
        {
            public string $foo;
            public string $baz;
            public string $foobar;
            public string $bafoo;
        };

        $this->assertEquals($class->foo, 'scratched 3 million dollars off my checkList 3 years ago');
        $this->assertEquals($class->baz, 'am tip toeing to the bank hope u dont speak to me');
        $this->assertEquals($class->foobar, 'bar_foo');
        $this->assertEquals($class->bafoo, 'out_of_foos');
    }

    /**
     * @dataProvider dtoData
     */
    public function test_cannot_have_default_properties(array $data): void
    {
        $this->expectException(PropertyCannotHaveDefaultValueException::class);

        $class = new class($data) extends DataTransferObject
        {
            protected string $value = 'foo';
        };
    }

    /**
     * @dataProvider dtoData
     */
    public function test_returns_parsed_attributes(array $data): void
    {
        $dto = new class($data) extends DataTransferObject
        {
        };

        $this->assertEquals($data, $dto->toArray());
    }

    /**
     * @dataProvider dtoData
     */
    public function test_cannot_change_data_transfer_attribute(array $data): void
    {
        $this->expectException(ChangeAttributeException::class);

        $dto = new class($data) extends DataTransferObject
        {
        };

        $dto['foo'] = 'change';
    }

    /**
     * @dataProvider dtoData
     */
    public function test_cannot_unset_data_transfer_object_attribute(array $data): void
    {
        $this->expectException(UnsetAttributeException::class);

        $dto = new class($data) extends DataTransferObject
        {
        };

        unset($dto['foo']);
    }
}
