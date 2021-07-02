<?php

declare(strict_types=1);

namespace Aedon\Test;

use Aedon\Expect;
use ArrayIterator;
use BadFunctionCallException;
use Error;
use InvalidArgumentException;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;
use RangeException;
use RuntimeException;
use SplFileInfo;
use stdClass;
use UnexpectedValueException;

class DerivedStdClass extends stdClass
{

}

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
class ExpectTest extends TestCase
{
    protected function setUp(): void
    {
        Expect::resetCustomExceptions();
    }

    public function testShouldUseProvidedCustomException(): void
    {
        Expect::registerCustomException('isTrue', InvalidArgumentException::class);

        self::expectException(InvalidArgumentException::class);

        Expect::isTrue(false);
    }

    public function testShouldUseProvidedCustomExceptionCallableWithException(): void
    {
        $value = false;

        Expect::registerCustomException('isTrue', function() use (&$value) {
            $value = true;
        });

        self::expectException(RuntimeException::class);

        Expect::isTrue(false);
        self::assertTrue($value);
    }

    public function testShouldUseProvidedCustomExceptionCallableWithoutException(): void
    {
        $value = false;

        Expect::registerCustomException('isTrue', function() use (&$value) {
            $value = true;
            return false;
        });

        Expect::isTrue(false);
        self::assertTrue($value);
    }

    public function testShouldResetCustomsExceptions(): void
    {
        Expect::registerCustomException('isTrue', InvalidArgumentException::class);

        self::expectException(InvalidArgumentException::class);

        Expect::isTrue(false);

        Expect::resetCustomExceptions();

        self::expectException(RuntimeException::class);

        Expect::isTrue(false);
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
    public function testIsTrueShouldThrowException(bool $value, bool $expectException): void
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
    public function testIsFalseShouldThrowException(bool $value, bool $expectException): void
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
     * @param mixed $value
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
     * @param mixed $value
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
     * @param mixed $value
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
     * @param mixed $value
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
     * @param mixed $value
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
     * @param mixed $value
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
     * @param mixed $value
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
     * @param mixed $value
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
     * @param mixed $value
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
     * @param mixed $value
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
     * @param mixed $value
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
     * @param mixed $value
     * @param mixed $maxValue
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
     * @param mixed $value
     * @param mixed $maxValue
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
     * @param mixed $value
     * @param mixed $minValue
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
     * @param mixed $value
     * @param mixed $minValue
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
     * @param mixed $value
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
     * @param mixed $value
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
     * @param mixed $value
     */
    public function testHasArrayValueShouldThrowException($value, bool $expectException): void
    {
        if ($expectException) {
            self::expectException(UnexpectedValueException::class);
        }

        $testArray = [1, 2, 3];

        Expect::hasArrayValue($value, $testArray);

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
     * @param mixed $value
     */
    public function testHasArrayKeyShouldThrowException($value, bool $expectException): void
    {
        if ($expectException) {
            self::expectException(UnexpectedValueException::class);
        }

        $testArray = [1 => 1, 2 => 2, 3 => 3];

        Expect::hasArrayKey($value, $testArray);

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
     * @param mixed $value
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
     * @param mixed $value
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
     * @param mixed $value
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
     * @param mixed $value
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
     * @param mixed $value
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
     * @param mixed $value
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

    public function provideIsIterableOfData(): array
    {
        return [
            [[new stdClass()], stdClass::class, false],
            [[1, 2, 3], stdClass::class, true],
            [new ArrayIterator([new stdClass(), new stdClass()]), stdClass::class, false],
            [new ArrayIterator([1, 2, 3]), SplFileInfo::class, true],
        ];
    }

    /**
     * @dataProvider provideIsIterableOfData
     * @param mixed $value
     */
    public function testIsIterableOfShouldThrowException(iterable $iterable, string $type, bool $expectException): void
    {
        if ($expectException) {
            self::expectException(InvalidArgumentException::class);
        }

        Expect::isIterableOf($iterable, $type);

        if (!$expectException) {
            self::assertTrue(true);
        }
    }
}