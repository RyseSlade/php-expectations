<?php

declare(strict_types=1);

namespace Aedon\Test;

use Aedon\Expect;
use BadFunctionCallException;
use Error;
use InvalidArgumentException;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;
use RangeException;
use RuntimeException;
use stdClass;
use UnexpectedValueException;

class ExpectTest extends TestCase
{
    public function testShouldThrowErrorOnConstruct(): void
    {
        self::expectException(Error::class);

        new Expect();
    }

    public function testIsTrueShouldThrowException(): void
    {
        self::expectException(RuntimeException::class);
        Expect::isTrue(false);
    }

    public function testIsFalseShouldThrowException(): void
    {
        self::expectException(RuntimeException::class);
        Expect::isFalse(true);
    }

    public function provideIsNotEmptyData(): array
    {
        return [
            [null],
            [[]],
            [0],
            [''],
            ['0'],
            [false],
            [0.0],
        ];
    }

    /**
     * @dataProvider provideIsNotEmptyData
     */
    public function testIsNotEmptyShouldThrowException($value): void
    {
        self::expectException(InvalidArgumentException::class);
        Expect::isNotEmpty($value);
    }

    public function provideIsNumericData(): array
    {
        return [
            [null],
            [[]],
            ['test'],
            [false],
            [new stdClass()],
            [function() {}],
        ];
    }

    /**
     * @dataProvider provideIsNumericData
     */
    public function testIsNumericShouldThrowException($value): void
    {
        self::expectException(InvalidArgumentException::class);
        Expect::isNumeric($value);
    }

    public function provideIsIntData(): array
    {
        return [
            [null],
            [[]],
            [''],
            ['0'],
            [false],
            [0.0],
            [new stdClass()],
            [function() {}],
        ];
    }

    /**
     * @dataProvider provideIsIntData
     */
    public function testIsIntShouldThrowException($value): void
    {
        self::expectException(InvalidArgumentException::class);
        Expect::isInt($value);
    }

    public function provideIsFloatData(): array
    {
        return [
            [null],
            [[]],
            [0],
            [''],
            ['0'],
            [false],
            [new stdClass()],
            [function() {}],
        ];
    }

    /**
     * @dataProvider provideIsFloatData
     */
    public function testIsFloatShouldThrowException($value): void
    {
        self::expectException(InvalidArgumentException::class);
        Expect::isFloat($value);
    }

    public function provideIsBoolData(): array
    {
        return [
            [null],
            [[]],
            [0],
            [''],
            ['0'],
            [0.0],
            [new stdClass()],
            [function() {}],
        ];
    }

    /**
     * @dataProvider provideIsBoolData
     */
    public function testIsBoolShouldThrowException($value): void
    {
        self::expectException(InvalidArgumentException::class);
        Expect::isBool($value);
    }

    public function provideIsObjectData(): array
    {
        return [
            [null],
            [[]],
            [0],
            [''],
            ['0'],
            [0.0],
            [false],
            [function() {}],
        ];
    }

    /**
     * @dataProvider provideIsObjectData
     */
    public function testIsObjectShouldThrowException($value): void
    {
        self::expectException(InvalidArgumentException::class);
        Expect::isObject($value);
    }

    public function provideIsStringData(): array
    {
        return [
            [null],
            [[]],
            [0],
            [0.0],
            [false],
            [new stdClass()],
            [function() {}],
        ];
    }

    /**
     * @dataProvider provideIsStringData
     */
    public function testIsStringShouldThrowException($value): void
    {
        self::expectException(InvalidArgumentException::class);
        Expect::isString($value);
    }

    public function provideIsArrayData(): array
    {
        return [
            [null],
            [0],
            [''],
            ['0'],
            [0.0],
            [false],
            [new stdClass()],
            [function() {}],
        ];
    }

    /**
     * @dataProvider provideIsArrayData
     */
    public function testIsArrayShouldThrowException($value): void
    {
        self::expectException(InvalidArgumentException::class);
        Expect::isArray($value);
    }

    public function provideIsInstanceOfData(): array
    {
        return [
            [null],
            [[]],
            [0],
            [''],
            ['0'],
            [0.0],
            [false],
            [function() {}],
        ];
    }

    /**
     * @dataProvider provideIsInstanceOfData
     */
    public function testIsInstanceOfShouldThrowException($value): void
    {
        self::expectException(UnexpectedValueException::class);
        Expect::isInstanceOf($value, stdClass::class);
    }

    public function provideIsNullData(): array
    {
        return [
            [[]],
            [0],
            [''],
            ['0'],
            [0.0],
            [false],
            [new stdClass()],
            [function() {}],
        ];
    }

    /**
     * @dataProvider provideIsNullData
     */
    public function testIsNullShouldThrowException($value): void
    {
        self::expectException(InvalidArgumentException::class);
        Expect::isNull($value);
    }

    public function provideIsNotNullData(): array
    {
        return [
            [null],
        ];
    }

    /**
     * @dataProvider provideIsNotNullData
     */
    public function testIsNotNullShouldThrowException($value): void
    {
        self::expectException(InvalidArgumentException::class);
        Expect::isNotNull($value);
    }

    public function provideIsLowerThanData(): array
    {
        return [
            [null],
            [[]],
            [5],
            [''],
            ['0'],
            [5.0],
            [false],
            [new stdClass()],
            [function() {}],
        ];
    }

    /**
     * @dataProvider provideIsLowerThanData
     */
    public function testIsLowerThanShouldThrowException($value): void
    {
        self::expectException(RangeException::class);
        Expect::isLowerThan($value, 5);
    }

    public function provideIsLowerThanOrEqualData(): array
    {
        return [
            [null],
            [[]],
            [6],
            [''],
            ['0'],
            [5.1],
            [false],
            [new stdClass()],
            [function() {}],
        ];
    }

    /**
     * @dataProvider provideIsLowerThanOrEqualData
     */
    public function testIsLowerThanOrEqualShouldThrowException($value): void
    {
        self::expectException(RangeException::class);
        Expect::isLowerThanOrEqual($value, 5);
    }

    public function provideIsGreaterThanData(): array
    {
        return [
            [null],
            [[]],
            [5],
            [''],
            ['0'],
            [5.0],
            [false],
            [new stdClass()],
            [function() {}],
        ];
    }

    /**
     * @dataProvider provideIsGreaterThanData
     */
    public function testIsGreaterThanOShouldThrowException($value): void
    {
        self::expectException(RangeException::class);
        Expect::isGreaterThan($value, 5);
    }

    public function provideIsGreaterThanOrEqualData(): array
    {
        return [
            [null],
            [[]],
            [6],
            [''],
            ['0'],
            [5.1],
            [false],
            [new stdClass()],
            [function() {}],
        ];
    }

    /**
     * @dataProvider provideIsGreaterThanOrEqualData
     */
    public function testIsGreaterThanOrEqualShouldThrowException($value): void
    {
        self::expectException(RangeException::class);
        Expect::isGreaterThanOrEqual($value, 5);
    }

    public function provideIsCallableData(): array
    {
        return [
            [null],
            [[]],
            [0],
            [''],
            ['0'],
            [0.0],
            [false],
            [new stdClass()],
        ];
    }

    /**
     * @dataProvider provideIsCallableData
     */
    public function testIsCallableShouldThrowException($value): void
    {
        self::expectException(BadFunctionCallException::class);
        Expect::isCallable($value);
    }

    public function provideIsInvokableData(): array
    {
        return [
            [null],
            [[]],
            [0],
            [''],
            ['0'],
            [0.0],
            [false],
            [new stdClass()],
        ];
    }

    /**
     * @dataProvider provideIsInvokableData
     */
    public function testIsInvokableShouldThrowException($value): void
    {
        self::expectException(BadFunctionCallException::class);
        Expect::isInvokable($value);
    }

    public function provideHasArrayValueData(): array
    {
        return [
            [null],
            [[]],
            [0],
            [''],
            ['0'],
            [0.0],
            [false],
            [new stdClass()],
            [function() {}],
        ];
    }

    /**
     * @dataProvider provideHasArrayValueData
     */
    public function testHasArrayValueShouldThrowException($value): void
    {
        self::expectException(UnexpectedValueException::class);
        Expect::hasArrayValue($value, [1, 2, 3]);
    }

    public function provideHasArrayKeyData(): array
    {
        return [
            [null],
            [[]],
            [0],
            [''],
            ['0'],
            [0.0],
            [false],
            [new stdClass()],
            [function() {}],
        ];
    }

    /**
     * @dataProvider provideHasArrayKeyData
     */
    public function testHasArrayKeyShouldThrowException($value): void
    {
        self::expectException(UnexpectedValueException::class);
        Expect::hasArrayKey($value, [1 => 1, 2 => 2, 3 => 3]);
    }

    public function testIsFileShouldThrowException(): void
    {
        // TODO
        $value = '';

        self::expectException(InvalidArgumentException::class);
        Expect::isFile($value);
    }

    public function testIsFileReadableShouldThrowException(): void
    {
        // TODO
        $value = '';

        self::expectException(InvalidArgumentException::class);
        Expect::isFileReadable($value);
    }

    public function testIsFileWritableShouldThrowException(): void
    {
        // TODO
        $value = '';

        self::expectException(InvalidArgumentException::class);
        Expect::isFileWritable($value);
    }

    public function testIsPathShouldThrowException(): void
    {
        // TODO
        $value = '';

        self::expectException(InvalidArgumentException::class);
        Expect::isPath($value);
    }

    public function testIsPathReadableShouldThrowException(): void
    {
        // TODO
        $value = '';

        self::expectException(InvalidArgumentException::class);
        Expect::isPathReadable($value);
    }

    public function testIsPathWritableShouldThrowException(): void
    {
        // TODO
        $value = '';

        self::expectException(InvalidArgumentException::class);
        Expect::isPathWritable($value);
    }

    public function provideIsCountableData(): array
    {
        return [
            [null],
            [0],
            [''],
            ['0'],
            [0.0],
            [false],
            [new stdClass()],
            [function() {}],
        ];
    }

    /**
     * @dataProvider provideIsCountableData
     */
    public function testIsCountableShouldThrowException($value): void
    {
        self::expectException(UnexpectedValueException::class);
        Expect::isCountable($value);
    }

    public function provideIsIterableData(): array
    {
        return [
            [null],
            [0],
            [''],
            ['0'],
            [0.0],
            [false],
            [new stdClass()],
            [function() {}],
        ];
    }

    /**
     * @dataProvider provideIsIterableData
     */
    public function testIsIterableShouldThrowException($value): void
    {
        self::expectException(UnexpectedValueException::class);
        Expect::isIterable($value);
    }

    public function provideIsResourceData(): array
    {
        return [
            [null],
            [[]],
            [0],
            [''],
            ['0'],
            [0.0],
            [false],
            [new stdClass()],
            [function() {}],
        ];
    }

    /**
     * @dataProvider provideIsResourceData
     */
    public function testIsResourceShouldThrowException($value): void
    {
        self::expectException(UnexpectedValueException::class);
        Expect::isResource($value);
    }

    public function provideIsSubclassOfData(): array
    {
        return [
            [new stdClass()],
            [function() {}],
        ];
    }

    /**
     * @dataProvider provideIsSubclassOfData
     */
    public function testIsSubclassOfShouldThrowException($value): void
    {
        self::expectException(UnexpectedValueException::class);
        Expect::isSubClassOf($value, stdClass::class);
    }
}