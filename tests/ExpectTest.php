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

class DerivedStdClass extends stdClass
{

}

class ExpectTest extends TestCase
{
    public function testShouldThrowErrorOnConstruct(): void
    {
        self::expectException(Error::class);

        new Expect();
    }

    public function provideIsTrueData(): array
    {
        return [
            [true, false],
            [false, true],
        ];
    }

    /**
     * @dataProvider provideIsTrueData
     */
    public function testIsTrueShouldThrowException($value, bool $expectException): void
    {
        if ($expectException) {
            self::expectException(RuntimeException::class);
        }

        Expect::isTrue($value);

        if (!$expectException) {
            self::assertTrue(true);
        }
    }

    public function provideIsFalseData(): array
    {
        return [
            [true, true],
            [false, false],
        ];
    }

    /**
     * @dataProvider provideIsFalseData
     */
    public function testIsFalseShouldThrowException($value, bool $expectException): void
    {
        if ($expectException) {
            self::expectException(RuntimeException::class);
        }

        Expect::isFalse($value);

        if (!$expectException) {
            self::assertTrue(true);
        }
    }

    public function provideIsNotEmptyData(): array
    {
        return [
            [null, true],
            [[], true],
            [[1, 2, 3], false],
            [0, true],
            [5, false],
            ['', true],
            ['test', false],
            ['0', true],
            ['0.0', true],
            ['5', false],
            ['5.5', false],
            [0.0, true],
            [5.5, false],
            [true, false],
            [false, true],
            [new stdClass(), false],
            [function() {}, false],
        ];
    }

    /**
     * @dataProvider provideIsNotEmptyData
     */
    public function testIsNotEmptyShouldThrowException($value, bool $expectException): void
    {
        if ($expectException) {
            self::expectException(InvalidArgumentException::class);
        }

        Expect::isNotEmpty($value);

        if (!$expectException) {
            self::assertTrue(true);
        }
    }

    public function provideIsNumericData(): array
    {
        return [
            [null, true],
            [[], true],
            [[1, 2, 3], true],
            [0, false],
            [5, false],
            ['', true],
            ['test', true],
            ['0', false],
            ['0.0', false],
            ['5', false],
            ['5.5', false],
            [0.0, false],
            [5.5, false],
            [true, true],
            [false, true],
            [new stdClass(), true],
            [function() {}, true],
        ];
    }

    /**
     * @dataProvider provideIsNumericData
     */
    public function testIsNumericShouldThrowException($value, bool $expectException): void
    {
        if ($expectException) {
            self::expectException(InvalidArgumentException::class);
        }

        Expect::isNumeric($value);

        if (!$expectException) {
            self::assertTrue(true);
        }
    }

    public function provideIsIntData(): array
    {
        return [
            [null, true],
            [[], true],
            [[1, 2, 3], true],
            [0, false],
            [5, false],
            ['', true],
            ['test', true],
            ['0', true],
            ['0.0', true],
            ['5', true],
            ['5.5', true],
            [0.0, true],
            [5.5, true],
            [true, true],
            [false, true],
            [new stdClass(), true],
            [function() {}, true],
        ];
    }

    /**
     * @dataProvider provideIsIntData
     */
    public function testIsIntShouldThrowException($value, bool $expectException): void
    {
        if ($expectException) {
            self::expectException(InvalidArgumentException::class);
        }

        Expect::isInt($value);

        if (!$expectException) {
            self::assertTrue(true);
        }
    }

    public function provideIsFloatData(): array
    {
        return [
            [null, true],
            [[], true],
            [[1, 2, 3], true],
            [0, true],
            [5, true],
            ['', true],
            ['test', true],
            ['0', true],
            ['0.0', true],
            ['5', true],
            ['5.5', true],
            [0.0, false],
            [5.5, false],
            [true, true],
            [false, true],
            [new stdClass(), true],
            [function() {}, true],
        ];
    }

    /**
     * @dataProvider provideIsFloatData
     */
    public function testIsFloatShouldThrowException($value, bool $expectException): void
    {
        if ($expectException) {
            self::expectException(InvalidArgumentException::class);
        }

        Expect::isFloat($value);

        if (!$expectException) {
            self::assertTrue(true);
        }
    }

    public function provideIsBoolData(): array
    {
        return [
            [null, true],
            [[], true],
            [[1, 2, 3], true],
            [0, true],
            [5, true],
            ['', true],
            ['test', true],
            ['0', true],
            ['0.0', true],
            ['5', true],
            ['5.5', true],
            [0.0, true],
            [5.5, true],
            [true, false],
            [false, false],
            [new stdClass(), true],
            [function() {}, true],
        ];
    }

    /**
     * @dataProvider provideIsBoolData
     */
    public function testIsBoolShouldThrowException($value, bool $expectException): void
    {
        if ($expectException) {
            self::expectException(InvalidArgumentException::class);
        }

        Expect::isBool($value);

        if (!$expectException) {
            self::assertTrue(true);
        }
    }

    public function provideIsObjectData(): array
    {
        return [
            [null, true],
            [[], true],
            [[1, 2, 3], true],
            [0, true],
            [5, true],
            ['', true],
            ['test', true],
            ['0', true],
            ['0.0', true],
            ['5', true],
            ['5.5', true],
            [0.0, true],
            [5.5, true],
            [true, true],
            [false, true],
            [new stdClass(), false],
            [function() {}, true],
        ];
    }

    /**
     * @dataProvider provideIsObjectData
     */
    public function testIsObjectShouldThrowException($value, bool $expectException): void
    {
        if ($expectException) {
            self::expectException(InvalidArgumentException::class);
        }

        Expect::isObject($value);

        if (!$expectException) {
            self::assertTrue(true);
        }
    }

    public function provideIsStringData(): array
    {
        return [
            [null, true],
            [[], true],
            [[1, 2, 3], true],
            [0, true],
            [5, true],
            ['', false],
            ['test', false],
            ['0', false],
            ['0.0', false],
            ['5', false],
            ['5.5', false],
            [0.0, true],
            [5.5, true],
            [true, true],
            [false, true],
            [new stdClass(), true],
            [function() {}, true],
        ];
    }

    /**
     * @dataProvider provideIsStringData
     */
    public function testIsStringShouldThrowException($value, bool $expectException): void
    {
        if ($expectException) {
            self::expectException(InvalidArgumentException::class);
        }

        Expect::isString($value);

        if (!$expectException) {
            self::assertTrue(true);
        }
    }

    public function provideIsArrayData(): array
    {
        return [
            [null, true],
            [[], false],
            [[1, 2, 3], false],
            [0, true],
            [5, true],
            ['', true],
            ['test', true],
            ['0', true],
            ['0.0', true],
            ['5', true],
            ['5.5', true],
            [0.0, true],
            [5.5, true],
            [true, true],
            [false, true],
            [new stdClass(), true],
            [function() {}, true],
        ];
    }

    /**
     * @dataProvider provideIsArrayData
     */
    public function testIsArrayShouldThrowException($value, bool $expectException): void
    {
        if ($expectException) {
            self::expectException(InvalidArgumentException::class);
        }

        Expect::isArray($value);

        if (!$expectException) {
            self::assertTrue(true);
        }
    }

    public function provideIsInstanceOfData(): array
    {
        return [
            [null, true],
            [[], true],
            [[1, 2, 3], true],
            [0, true],
            [5, true],
            ['', true],
            ['test', true],
            ['0', true],
            ['0.0', true],
            ['5', true],
            ['5.5', true],
            [0.0, true],
            [5.5, true],
            [true, true],
            [false, true],
            [new stdClass(), false],
            [function() {}, true],
        ];
    }

    /**
     * @dataProvider provideIsInstanceOfData
     */
    public function testIsInstanceOfShouldThrowException($value, bool $expectException): void
    {
        if ($expectException) {
            self::expectException(UnexpectedValueException::class);
        }

        Expect::isInstanceOf($value, stdClass::class);

        if (!$expectException) {
            self::assertTrue(true);
        }
    }

    public function provideIsNullData(): array
    {
        return [
            [null, false],
            [[], true],
            [[1, 2, 3], true],
            [0, true],
            [5, true],
            ['', true],
            ['test', true],
            ['0', true],
            ['0.0', true],
            ['5', true],
            ['5.5', true],
            [0.0, true],
            [5.5, true],
            [true, true],
            [false, true],
            [new stdClass(), true],
            [function() {}, true],
        ];
    }

    /**
     * @dataProvider provideIsNullData
     */
    public function testIsNullShouldThrowException($value, bool $expectException): void
    {
        if ($expectException) {
            self::expectException(InvalidArgumentException::class);
        }

        Expect::isNull($value);

        if (!$expectException) {
            self::assertTrue(true);
        }
    }

    public function provideIsNotNullData(): array
    {
        return [
            [null, true],
            [[], false],
            [[1, 2, 3], false],
            [0, false],
            [5, false],
            ['', false],
            ['test', false],
            ['0', false],
            ['0.0', false],
            ['5', false],
            ['5.5', false],
            [0.0, false],
            [5.5, false],
            [false, false],
            [false, false],
            [new stdClass(), false],
            [function() {}, false],
        ];
    }

    /**
     * @dataProvider provideIsNotNullData
     */
    public function testIsNotNullShouldThrowException($value, bool $expectException): void
    {
        if ($expectException) {
            self::expectException(InvalidArgumentException::class);
        }

        Expect::isNotNull($value);

        if (!$expectException) {
            self::assertTrue(true);
        }
    }

    public function provideIsLowerThanData(): array
    {
        return [
            [null, 5, true],
            [[], 5, true],
            [[1, 2, 3], 5, true],
            [0, 5, false],
            [5, 5, true],
            ['', 5, true],
            ['test', 5, true],
            ['0', 5, true],
            ['0.0', 5, true],
            ['5', 5, true],
            ['5.5', 5, true],
            [0.0, 5, false],
            [5.5, 5, true],
            [true, 5, true],
            [false, 5, true],
            [new stdClass(), 5, true],
            [function() {}, 5, true],
            [5.0, 5.1, false],
        ];
    }

    /**
     * @dataProvider provideIsLowerThanData
     */
    public function testIsLowerThanShouldThrowException($value, $maxValue, bool $expectException): void
    {
        if ($expectException) {
            self::expectException(RangeException::class);
        }

        Expect::isLowerThan($value, $maxValue);

        if (!$expectException) {
            self::assertTrue(true);
        }
    }

    public function provideIsLowerThanOrEqualData(): array
    {
        return [
            [null, 5, true],
            [[], 5, true],
            [[1, 2, 3], 5, true],
            [0, 5, false],
            [5, 5, false],
            ['', 5, true],
            ['test', 5, true],
            ['0', 5, true],
            ['0.0', 5, true],
            ['5', 5, true],
            ['5.5', 5, true],
            [0.0, 5, false],
            [5.5, 5, true],
            [true, 5, true],
            [false, 5, true],
            [new stdClass(), 5, true],
            [function() {}, 5, true],
            [5.1, 5.1, false],
        ];
    }

    /**
     * @dataProvider provideIsLowerThanOrEqualData
     */
    public function testIsLowerThanOrEqualShouldThrowException($value, $maxValue, bool $expectException): void
    {
        if ($expectException) {
            self::expectException(RangeException::class);
        }

        Expect::isLowerThanOrEqual($value, $maxValue);

        if (!$expectException) {
            self::assertTrue(true);
        }
    }

    public function provideIsGreaterThanData(): array
    {
        return [
            [null, 5, true],
            [[], 5, true],
            [[1, 2, 3], 5, true],
            [0, 5, true],
            [5, 5, true],
            ['', 5, true],
            ['test', 5, true],
            ['0', 5, true],
            ['0.0', 5, true],
            ['5', 5, true],
            ['5.5', 5, true],
            [0.0, 5, true],
            [5.5, 5, false],
            [true, 5, true],
            [false, 5, true],
            [new stdClass(), 5, true],
            [function() {}, 5, true],
            [5.1, 5.0, false],
        ];
    }

    /**
     * @dataProvider provideIsGreaterThanData
     */
    public function testIsGreaterThanOShouldThrowException($value, $minValue, bool $expectException): void
    {
        if ($expectException) {
            self::expectException(RangeException::class);
        }

        Expect::isGreaterThan($value, $minValue);

        if (!$expectException) {
            self::assertTrue(true);
        }
    }

    public function provideIsGreaterThanOrEqualData(): array
    {
        return [
            [null, 5, true],
            [[], 5, true],
            [[1, 2, 3], 5, true],
            [0, 5, true],
            [5, 5, false],
            ['', 5, true],
            ['test', 5, true],
            ['0', 5, true],
            ['0.0', 5, true],
            ['5', 5, true],
            ['5.5', 5, true],
            [0.0, 5, true],
            [5.5, 5, false],
            [true, 5, true],
            [false, 5, true],
            [new stdClass(), 5, true],
            [function() {}, 5, true],
            [5.1, 5.1, false],
        ];
    }

    /**
     * @dataProvider provideIsGreaterThanOrEqualData
     */
    public function testIsGreaterThanOrEqualShouldThrowException($value, $minValue, bool $expectException): void
    {
        if ($expectException) {
            self::expectException(RangeException::class);
        }

        Expect::isGreaterThanOrEqual($value, $minValue);

        if (!$expectException) {
            self::assertTrue(true);
        }
    }

    public function provideIsCallableData(): array
    {
        return [
            [null, true],
            [[], true],
            [[1, 2, 3], true],
            [0, true],
            [5, true],
            ['', true],
            ['test', true],
            ['0', true],
            ['0.0', true],
            ['5', true],
            ['5.5', true],
            [0.0, true],
            [5.5, true],
            [true, true],
            [false, true],
            [new stdClass(), true],
            [function() {}, false],
        ];
    }

    /**
     * @dataProvider provideIsCallableData
     */
    public function testIsCallableShouldThrowException($value, bool $expectException): void
    {
        if ($expectException) {
            self::expectException(BadFunctionCallException::class);
        }

        Expect::isCallable($value);

        if (!$expectException) {
            self::assertTrue(true);
        }
    }

    public function provideIsInvokableData(): array
    {
        return [
            [null, true],
            [[], true],
            [[1, 2, 3], true],
            [0, true],
            [5, true],
            ['', true],
            ['test', true],
            ['0', true],
            ['0.0', true],
            ['5', true],
            ['5.5', true],
            [0.0, true],
            [5.5, true],
            [true, true],
            [false, true],
            [new stdClass(), true],
            [function() {}, false],
        ];
    }

    /**
     * @dataProvider provideIsInvokableData
     */
    public function testIsInvokableShouldThrowException($value, bool $expectException): void
    {
        if ($expectException) {
            self::expectException(BadFunctionCallException::class);
        }

        Expect::isInvokable($value);

        if (!$expectException) {
            self::assertTrue(true);
        }
    }

    public function provideHasArrayValueData(): array
    {
        return [
            [null, true],
            [[], true],
            [[1, 2, 3], true],
            [0, true],
            [5, true],
            ['', true],
            ['test', true],
            ['0', true],
            ['0.0', true],
            ['5', true],
            ['5.5', true],
            [0.0, true],
            [5.5, true],
            [true, true],
            [false, true],
            [new stdClass(), true],
            [function() {}, true],
            [1, false],
            [2, false],
            [3, false],
        ];
    }

    /**
     * @dataProvider provideHasArrayValueData
     */
    public function testHasArrayValueShouldThrowException($value, bool $expectException): void
    {
        if ($expectException) {
            self::expectException(UnexpectedValueException::class);
        }

        Expect::hasArrayValue($value, [1, 2, 3]);

        if (!$expectException) {
            self::assertTrue(true);
        }
    }

    public function provideHasArrayKeyData(): array
    {
        return [
            [null, true],
            [[], true],
            [[1, 2, 3], true],
            [0, true],
            [5, true],
            ['', true],
            ['test', true],
            ['0', true],
            ['0.0', true],
            ['5', true],
            ['5.5', true],
            [0.0, true],
            [5.5, true],
            [true, true],
            [false, true],
            [new stdClass(), true],
            [function() {}, true],
            [1, false],
            [2, false],
            [3, false],
        ];
    }

    /**
     * @dataProvider provideHasArrayKeyData
     */
    public function testHasArrayKeyShouldThrowException($value, bool $expectException): void
    {
        if ($expectException) {
            self::expectException(UnexpectedValueException::class);
        }

        Expect::hasArrayKey($value, [1 => 1, 2 => 2, 3 => 3]);

        if (!$expectException) {
            self::assertTrue(true);
        }
    }

    public function testIsFileShouldThrowException(): void
    {
        $vfs = vfsStream::setup('var', null, [
            'test.log' => '',
        ]);

        $value = $vfs->getChild('var/test.log')->url();
        Expect::isFile($value);

        $value = $vfs->url();
        self::expectException(InvalidArgumentException::class);
        Expect::isFile($value);
    }

    public function testIsReadableFileShouldThrowException(): void
    {
        $vfs = vfsStream::setup('var', null, [
            'test.log' => '',
        ]);

        $value = $vfs->getChild('var/test.log')->url();
        Expect::isReadableFile($value);

        $value = $vfs->url();
        self::expectException(InvalidArgumentException::class);
        Expect::isReadableFile($value);
    }

    public function testIsWritableFileShouldThrowException(): void
    {
        $vfs = vfsStream::setup('var', null, [
            'test.log' => '',
        ]);

        $value = $vfs->getChild('var/test.log')->url();
        Expect::isWritableFile($value);

        $value = $vfs->url();
        self::expectException(InvalidArgumentException::class);
        Expect::isWritableFile($value);
    }

    public function testIsPathShouldThrowException(): void
    {
        $vfs = vfsStream::setup('var', null, [
            'test.log' => '',
        ]);

        $value = $vfs->url();
        Expect::isPath($value);

        $value = $vfs->getChild('var/test.log')->url();
        self::expectException(InvalidArgumentException::class);
        Expect::isPath($value);
    }

    public function testIsReadablePathShouldThrowException(): void
    {
        $vfs = vfsStream::setup('var', null, [
            'test.log' => '',
        ]);

        $value = $vfs->url();
        Expect::isReadablePath($value);

        $value = $vfs->getChild('var/test.log')->url();
        self::expectException(InvalidArgumentException::class);
        Expect::isReadablePath($value);
    }

    public function testIsWritablePathShouldThrowException(): void
    {
        $vfs = vfsStream::setup('var', null, [
            'test.log' => '',
        ]);

        $value = $vfs->url();
        Expect::isWritablePath($value);

        $value = $vfs->getChild('var/test.log')->url();
        self::expectException(InvalidArgumentException::class);
        Expect::isWritablePath($value);
    }

    public function provideIsCountableData(): array
    {
        return [
            [null, true],
            [[], false],
            [[1, 2, 3], false],
            [0, true],
            [5, true],
            ['', true],
            ['test', true],
            ['0', true],
            ['0.0', true],
            ['5', true],
            ['5.5', true],
            [0.0, true],
            [5.5, true],
            [true, true],
            [false, true],
            [new stdClass(), true],
            [function() {}, true],
        ];
    }

    /**
     * @dataProvider provideIsCountableData
     */
    public function testIsCountableShouldThrowException($value, bool $expectException): void
    {
        if ($expectException) {
            self::expectException(UnexpectedValueException::class);
        }

        Expect::isCountable($value);

        if (!$expectException) {
            self::assertTrue(true);
        }
    }

    public function provideIsIterableData(): array
    {
        return [
            [null, true],
            [[], false],
            [[1, 2, 3], false],
            [0, true],
            [5, true],
            ['', true],
            ['test', true],
            ['0', true],
            ['0.0', true],
            ['5', true],
            ['5.5', true],
            [0.0, true],
            [5.5, true],
            [true, true],
            [false, true],
            [new stdClass(), true],
            [function() {}, true],
        ];
    }

    /**
     * @dataProvider provideIsIterableData
     */
    public function testIsIterableShouldThrowException($value, bool $expectException): void
    {
        if ($expectException) {
            self::expectException(UnexpectedValueException::class);
        }

        Expect::isIterable($value);

        if (!$expectException) {
            self::assertTrue(true);
        }
    }

    public function provideIsResourceData(): array
    {
        return [
            [null, true],
            [[], true],
            [[1, 2, 3], true],
            [0, true],
            [5, true],
            ['', true],
            ['test', true],
            ['0', true],
            ['0.0', true],
            ['5', true],
            ['5.5', true],
            [0.0, true],
            [5.5, true],
            [true, true],
            [false, true],
            [new stdClass(), true],
            [function() {}, true],
        ];
    }

    /**
     * @dataProvider provideIsResourceData
     */
    public function testIsResourceShouldThrowException($value, bool $expectException): void
    {
        if ($expectException) {
            self::expectException(UnexpectedValueException::class);
        }

        Expect::isResource($value);

        if (!$expectException) {
            self::assertTrue(true);
        }
    }

    public function provideIsSubclassOfData(): array
    {
        return [
            [null, true],
            [[], true],
            [[1, 2, 3], true],
            [0, true],
            [5, true],
            ['', true],
            ['test', true],
            ['0', true],
            ['0.0', true],
            ['5', true],
            ['5.5', true],
            [0.0, true],
            [5.5, true],
            [true, true],
            [false, true],
            [new stdClass(), true],
            [function() {}, true],
            [new DerivedStdClass(), false],
        ];
    }

    /**
     * @dataProvider provideIsSubclassOfData
     */
    public function testIsSubclassOfShouldThrowException($value, bool $expectException): void
    {
        if ($expectException) {
            self::expectException(UnexpectedValueException::class);
        }

        Expect::isSubClassOf($value, stdClass::class);

        if (!$expectException) {
            self::assertTrue(true);
        }
    }

    public function provideIsNotFalseData(): array
    {
        return [
            [null, false],
            [[], false],
            [[1, 2, 3], false],
            [0, false],
            [5, false],
            ['', false],
            ['test', false],
            ['0', false],
            ['0.0', false],
            ['5', false],
            ['5.5', false],
            [0.0, false],
            [5.5, false],
            [true, false],
            [false, true],
            [new stdClass(), false],
            [function() {}, false],
        ];
    }

    /**
     * @dataProvider provideIsNotFalseData
     */
    public function testIsNotFalseShouldThrowException($value, bool $expectException): void
    {
        if ($expectException) {
            self::expectException(InvalidArgumentException::class);
        }

        Expect::isNotFalse($value);

        if (!$expectException) {
            self::assertTrue(true);
        }
    }

    public function provideIsNotTrueData(): array
    {
        return [
            [null, false],
            [[], false],
            [[1, 2, 3], false],
            [0, false],
            [5, false],
            ['', false],
            ['test', false],
            ['0', false],
            ['0.0', false],
            ['5', false],
            ['5.5', false],
            [0.0, false],
            [5.5, false],
            [false, false],
            [true, true],
            [new stdClass(), false],
            [function() {}, false],
        ];
    }

    /**
     * @dataProvider provideIsNotTrueData
     */
    public function testIsNotTrueShouldThrowException($value, bool $expectException): void
    {
        if ($expectException) {
            self::expectException(InvalidArgumentException::class);
        }

        Expect::isNotTrue($value);

        if (!$expectException) {
            self::assertTrue(true);
        }
    }
}