<?php

declare(strict_types=1);

namespace Aedon;

use BadFunctionCallException;
use InvalidArgumentException;
use RangeException;
use RuntimeException;
use Throwable;
use UnexpectedValueException;

use function array_key_exists;
use function call_user_func;
use function file_exists;
use function in_array;
use function is_bool;
use function is_callable;
use function is_countable;
use function is_dir;
use function is_file;
use function is_float;
use function is_int;
use function is_iterable;
use function is_numeric;
use function is_object;
use function is_readable;
use function is_resource;
use function is_string;
use function is_subclass_of;
use function is_writable;

class Expect
{
    /** @var array<string, class-string<Throwable>|callable> */
    static protected array $customExceptions = [];

    /**
     * @psalm-param class-string<Throwable> $exceptionType
     */
    static protected function throwException(string $issuer, string $exceptionType, string $message): void
    {
        $customException = self::$customExceptions[$issuer] ?? null;

        if ($customException) {
            if (is_callable($customException)) {
                /** @var mixed $returnValue */
                $returnValue = call_user_func($customException);

                if ($returnValue === false) {
                    return;
                }
            } else {
                /** @psalm-var class-string<Throwable> $exceptionType */
                $exceptionType = self::$customExceptions[$issuer];
            }
        }

        throw new $exceptionType($message);
    }

    /**
     * @param class-string<Throwable>|callable $handler
     */
    static public function registerCustomException(string $method, string|callable $handler): void
    {
        if (!is_callable($handler) && !is_subclass_of($handler, Throwable::class)) {
            throw new InvalidArgumentException();
        }

        self::$customExceptions[$method] = $handler;
    }

    static public function resetCustomExceptions(): void
    {
        self::$customExceptions = [];
    }

    static public function isTrue(bool $condition): void
    {
        if ($condition === true) {
            return;
        }

        self::throwException(__FUNCTION__, RuntimeException::class, 'Expression must be true');
    }

    static public function isFalse(bool $condition): void
    {
        if ($condition === false) {
            return;
        }

        self::throwException(__FUNCTION__, RuntimeException::class, 'Expression must be false');
    }

    static public function isNotEmpty(mixed &$value): void
    {
        if ($value !== null && !empty($value) && $value !== '0.0') {
            return;
        }

        self::throwException(__FUNCTION__, InvalidArgumentException::class, 'Value must not be empty');
    }

    static public function isNumeric(mixed &$value): void
    {
        if (is_int($value) || (is_string($value) && is_numeric($value)) || is_float($value)) {
            return;
        }

        self::throwException(__FUNCTION__, InvalidArgumentException::class, 'Value must be numeric');
    }

    static public function isInt(mixed &$value): void
    {
        if (is_int($value)) {
            return;
        }

        self::throwException(__FUNCTION__, InvalidArgumentException::class, 'Value must be integer');
    }

    static public function isFloat(mixed &$value): void
    {
        if (is_float($value)) {
            return;
        }

        self::throwException(__FUNCTION__, InvalidArgumentException::class, 'Value must be float');
    }

    static public function isBool(mixed &$value): void
    {
        if (is_bool($value)) {
            return;
        }

        self::throwException(__FUNCTION__, InvalidArgumentException::class, 'Value must be boolean');
    }

    static public function isObject(mixed &$value): void
    {
        if (is_object($value) && !is_callable($value)) {
            return;
        }

        self::throwException(__FUNCTION__, InvalidArgumentException::class, 'Value must be an object');
    }

    static public function isString(mixed &$value): void
    {
        if (is_string($value)) {
            return;
        }

        self::throwException(__FUNCTION__, InvalidArgumentException::class, 'Value must be string');
    }

    static public function isArray(mixed &$value): void
    {
        if (is_array($value)) {
            return;
        }

        self::throwException(__FUNCTION__, InvalidArgumentException::class, 'Value must be array');
    }

    /**
     * @psalm-param class-string $expectedType
     */
    static public function isInstanceOf(mixed &$value, string $expectedType): void
    {
        if ($value instanceof $expectedType) {
            return;
        }

        self::throwException(__FUNCTION__, UnexpectedValueException::class, 'Value must be instance of ' . $expectedType);
    }

    static public function isNull(mixed &$value): void
    {
        if ($value === null) {
            return;
        }

        self::throwException(__FUNCTION__, InvalidArgumentException::class, 'Value must be null');
    }

    static public function isNotNull(mixed &$value): void
    {
        if ($value !== null) {
            return;
        }

        self::throwException(__FUNCTION__, InvalidArgumentException::class, 'Value must not be null');
    }

    static public function isLowerThan(mixed &$value, int|float $maxValue): void
    {
        if ((is_int($value) || is_float($value)) && $value < $maxValue) {
            return;
        }

        self::throwException(__FUNCTION__, RangeException::class, 'Value must be lower than');
    }

    static public function isLowerThanOrEqual(mixed &$value, int|float $maxValue): void
    {
        if ((is_int($value) || is_float($value)) && $value <= $maxValue) {
            return;
        }

        self::throwException(__FUNCTION__, RangeException::class, 'Value must be lower than or equal');
    }

    static public function isGreaterThan(mixed &$value, int|float $minValue): void
    {
        if ((is_int($value) || is_float($value)) && $value > $minValue) {
            return;
        }

        self::throwException(__FUNCTION__, RangeException::class, 'Value must be greater than');
    }

    static public function isGreaterThanOrEqual(mixed &$value, int|float $minValue): void
    {
        if ((is_int($value) || is_float($value)) && $value >= $minValue) {
            return;
        }

        self::throwException(__FUNCTION__, RangeException::class, 'Value must be greater than or equal');
    }

    static public function isCallable(mixed &$value): void
    {
        if (is_callable($value)) {
            return;
        }

        self::throwException(__FUNCTION__, BadFunctionCallException::class, 'Value must be callable');
    }

    static public function isInvokable(mixed &$value): void
    {
        if (is_callable([$value, '__invoke'])) {
            return;
        }

        self::throwException(__FUNCTION__, BadFunctionCallException::class, 'Value must be invokable');
    }

    static public function hasArrayValue(mixed $value, array &$array): void
    {
        if (in_array($value, $array, true)) {
            return;
        }

        self::throwException(__FUNCTION__, UnexpectedValueException::class, 'Value must be in array');
    }

    static public function hasArrayKey(mixed $value, array &$array): void
    {
        if ((is_string($value) || is_int($value)) && array_key_exists($value, $array) !== false) {
            return;
        }

        self::throwException(__FUNCTION__, UnexpectedValueException::class, 'Value must be array key');
    }

    static public function isFile(string &$file): void
    {
        if (file_exists($file) && is_file($file)) {
            return;
        }

        self::throwException(__FUNCTION__, InvalidArgumentException::class, 'File must be valid');
    }

    static public function isReadableFile(string &$file): void
    {
        if (file_exists($file) && is_file($file) && is_readable($file)) {
            return;
        }

        self::throwException(__FUNCTION__, InvalidArgumentException::class, 'File must be readable');
    }

    static public function isWritableFile(string &$file): void
    {
        if (file_exists($file) && is_file($file) && is_writable($file)) {
            return;
        }

        self::throwException(__FUNCTION__, InvalidArgumentException::class, 'File must be writable');
    }

    static public function isPath(string &$path): void
    {
        if (is_dir($path)) {
            return;
        }

        self::throwException(__FUNCTION__, InvalidArgumentException::class, 'Path must be valid');
    }

    static public function isReadablePath(string &$path): void
    {
        if (is_dir($path) && is_readable($path)) {
            return;
        }

        self::throwException(__FUNCTION__, InvalidArgumentException::class, 'Path must be readable');
    }

    static public function isWritablePath(string &$path): void
    {
        if (is_dir($path) && is_writable($path)) {
            return;
        }

        self::throwException(__FUNCTION__, InvalidArgumentException::class, 'Path must be writable');
    }

    static public function isCountable(mixed &$value): void
    {
        if (is_countable($value)) {
            return;
        }

        self::throwException(__FUNCTION__, UnexpectedValueException::class, 'Value must be countable');
    }

    static public function isIterable(mixed &$value): void
    {
        if (is_iterable($value)) {
            return;
        }

        self::throwException(__FUNCTION__, UnexpectedValueException::class, 'Value must be an iterable');
    }

    static public function isResource(mixed &$value): void
    {
        if (is_resource($value)) {
            return;
        }

        self::throwException(__FUNCTION__, UnexpectedValueException::class, 'Value must be a resource');
    }

    /**
     * @psalm-param class-string $parentClass
     */
    static public function isSubClassOf(mixed &$object, string $parentClass): void
    {
        if ((is_string($object) || is_object($object)) && is_subclass_of($object, $parentClass, true)) {
            return;
        }

        self::throwException(__FUNCTION__, UnexpectedValueException::class, 'Object must be a subclass of ' . $parentClass);
    }

    static public function isNotFalse(mixed &$value): void
    {
        if ($value !== false) {
            return;
        }

        self::throwException(__FUNCTION__, InvalidArgumentException::class, 'Value must not be false');
    }

    static public function isNotTrue(mixed &$value): void
    {
        if ($value !== true) {
            return;
        }

        self::throwException(__FUNCTION__, InvalidArgumentException::class, 'Value must not be true');
    }

    /**
     * @param iterable<mixed> $iterable
     */
    static public function isIterableOf(iterable &$iterable, string|callable $type): void
    {
        $expectInstanceOf = is_string($type);
        $expectCallable = is_callable($type);

        /** @var mixed $item */
        foreach ($iterable as $item) {
            if (($expectInstanceOf && !$item instanceof $type) || ($expectCallable && !$type($item))) {
                self::throwException(__FUNCTION__, InvalidArgumentException::class, 'Iterable contains invalid items');
            }
        }
    }
}
