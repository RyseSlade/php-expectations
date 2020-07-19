<?php

declare(strict_types=1);

namespace Aedon;

use BadFunctionCallException;
use InvalidArgumentException;
use RangeException;
use RuntimeException;
use UnexpectedValueException;
use function array_key_exists;
use function array_search;
use function explode;
use function file_exists;
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

final class Expect
{
    /** @var string[] */
    static private $customExceptions = [];

    private function __construct()
    {

    }

    static private function throwException(string $issuer, string $exceptionType, string $message): void
    {
        $parts = explode('::', $issuer);

        if (isset($parts[1])) {
            if (isset(self::$customExceptions[$parts[1]])) {
                $exceptionType = self::$customExceptions[$parts[1]];
            }
        }

        throw new $exceptionType($message);
    }

    static public function registerCustomException(string $method, string $exceptionType): void
    {
        self::$customExceptions[$method] = $exceptionType;
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

        self::throwException(__METHOD__, RuntimeException::class, 'Expression must be true');
    }

    static public function isFalse(bool $condition): void
    {
        if ($condition === false) {
            return;
        }

        self::throwException(__METHOD__, RuntimeException::class, 'Expression must be false');
    }

    /**
     * @param mixed $value
     */
    static public function isNotEmpty($value): void
    {
        if ($value !== null && !empty($value) && $value !== '0.0') {
            return;
        }

        self::throwException(__METHOD__, InvalidArgumentException::class, 'Value must not be empty');
    }

    /**
     * @param mixed $value
     */
    static public function isNumeric($value): void
    {
        if (is_int($value) || (is_string($value) && is_numeric($value)) || is_float($value)) {
            return;
        }

        self::throwException(__METHOD__, InvalidArgumentException::class, 'Value must be numeric');
    }

    /**
     * @param mixed $value
     */
    static public function isInt($value): void
    {
        if (is_int($value)) {
            return;
        }

        self::throwException(__METHOD__, InvalidArgumentException::class, 'Value must be integer');
    }

    /**
     * @param mixed $value
     */
    static public function isFloat($value): void
    {
        if (is_float($value)) {
            return;
        }

        self::throwException(__METHOD__, InvalidArgumentException::class, 'Value must be float');
    }

    /**
     * @param mixed $value
     */
    static public function isBool($value): void
    {
        if (is_bool($value)) {
            return;
        }

        self::throwException(__METHOD__, InvalidArgumentException::class, 'Value must be boolean');
    }

    /**
     * @param mixed $value
     */
    static public function isObject($value): void
    {
        if (is_object($value) && !is_callable($value)) {
            return;
        }

        self::throwException(__METHOD__, InvalidArgumentException::class, 'Value must be an object');
    }

    /**
     * @param mixed $value
     */
    static public function isString($value): void
    {
        if (is_string($value)) {
            return;
        }

        self::throwException(__METHOD__, InvalidArgumentException::class, 'Value must be string');
    }

    /**
     * @param mixed $value
     */
    static public function isArray($value): void
    {
        if (is_array($value)) {
            return;
        }

        self::throwException(__METHOD__, InvalidArgumentException::class, 'Value must be array');
    }

    /**
     * @param mixed $value
     * @param string $expectedType
     */
    static public function isInstanceOf($value, string $expectedType): void
    {
        if ($value instanceof $expectedType) {
            return;
        }

        self::throwException(__METHOD__, UnexpectedValueException::class, 'Value must be instance of ' . $expectedType);
    }

    /**
     * @param mixed $value
     */
    static public function isNull($value): void
    {
        if ($value === null) {
            return;
        }

        self::throwException(__METHOD__, InvalidArgumentException::class, 'Value must be null');
    }

    /**
     * @param mixed $value
     */
    static public function isNotNull($value): void
    {
        if ($value !== null) {
            return;
        }

        self::throwException(__METHOD__, InvalidArgumentException::class, 'Value must not be null');
    }

    /**
     * @param mixed $value
     * @param mixed $maxValue
     */
    static public function isLowerThan($value, $maxValue): void
    {
        if ((is_int($value) || is_float($value)) && (is_int($maxValue) || is_float($maxValue)) && $value < $maxValue) {
            return;
        }

        self::throwException(__METHOD__, RangeException::class, 'Value must be lower than ' . $maxValue);
    }

    /**
     * @param mixed $value
     * @param mixed $maxValue
     */
    static public function isLowerThanOrEqual($value, $maxValue): void
    {
        if ((is_int($value) || is_float($value)) && (is_int($maxValue) || is_float($maxValue)) && $value <= $maxValue) {
            return;
        }

        self::throwException(__METHOD__, RangeException::class, 'Value must be lower than or equal ' . $maxValue);
    }

    /**
     * @param mixed $value
     * @param mixed $minValue
     */
    static public function isGreaterThan($value, $minValue): void
    {
        if ((is_int($value) || is_float($value)) && (is_int($minValue) || is_float($minValue)) && $value > $minValue) {
            return;
        }

        self::throwException(__METHOD__, RangeException::class, 'Value must be greater than ' . $minValue);
    }

    /**
     * @param mixed $value
     * @param mixed $minValue
     */
    static public function isGreaterThanOrEqual($value, $minValue): void
    {
        if ((is_int($value) || is_float($value)) && (is_int($minValue) || is_float($minValue)) && $value >= $minValue) {
            return;
        }

        self::throwException(__METHOD__, RangeException::class, 'Value must be greater than or equal ' . $minValue);
    }

    /**
     * @param mixed $value
     */
    static public function isCallable($value): void
    {
        if (is_callable($value)) {
            return;
        }

        self::throwException(__METHOD__, BadFunctionCallException::class, 'Value must be callable');
    }

    /**
     * @param mixed $value
     */
    static public function isInvokable($value): void
    {
        if (is_callable([$value, '__invoke'])) {
            return;
        }

        self::throwException(__METHOD__, BadFunctionCallException::class, 'Value must be invokable');
    }

    /**
     * @param mixed $value
     * @param mixed[] $array
     */
    static public function hasArrayValue($value, array $array): void
    {
        if ((is_string($value) || is_numeric($value)) && array_search($value, $array) !== false) {
            return;
        }

        self::throwException(__METHOD__, UnexpectedValueException::class, 'Value must be in array');
    }

    /**
     * @param mixed $value
     * @param mixed[] $array
     */
    static public function hasArrayKey($value, array $array): void
    {
        if ((is_string($value) || is_int($value)) && array_key_exists($value, $array) !== false) {
            return;
        }

        self::throwException(__METHOD__, UnexpectedValueException::class, 'Value must be array key');
    }

    /**
     * @param string $file
     */
    static public function isFile(string $file): void
    {
        if (file_exists($file) && is_file($file)) {
            return;
        }

        self::throwException(__METHOD__, InvalidArgumentException::class, 'File must be valid');
    }

    /**
     * @param string $file
     */
    static public function isReadableFile(string $file): void
    {
        if (file_exists($file) && is_file($file) && is_readable($file)) {
            return;
        }

        self::throwException(__METHOD__, InvalidArgumentException::class, 'File must be readable');
    }

    /**
     * @param string $file
     */
    static public function isWritableFile(string $file): void
    {
        if (file_exists($file) && is_file($file) && is_writable($file)) {
            return;
        }

        self::throwException(__METHOD__, InvalidArgumentException::class, 'File must be writable');
    }

    /**
     * @param string $path
     */
    static public function isPath(string $path): void
    {
        if (is_dir($path)) {
            return;
        }

        self::throwException(__METHOD__, InvalidArgumentException::class, 'Path must be valid');
    }

    /**
     * @param string $path
     */
    static public function isReadablePath(string $path): void
    {
        if (is_dir($path) && is_readable($path)) {
            return;
        }

        self::throwException(__METHOD__, InvalidArgumentException::class, 'Path must be readable');
    }

    /**
     * @param string $path
     */
    static public function isWritablePath(string $path): void
    {
        if (is_dir($path) && is_writable($path)) {
            return;
        }

        self::throwException(__METHOD__, InvalidArgumentException::class, 'Path must be writable');
    }

    /**
     * @param mixed $value
     */
    static public function isCountable($value): void
    {
        if (is_countable($value)) {
            return;
        }

        self::throwException(__METHOD__, UnexpectedValueException::class, 'Value must be countable');
    }

    /**
     * @param mixed $value
     */
    static public function isIterable($value): void
    {
        if (is_iterable($value)) {
            return;
        }

        self::throwException(__METHOD__, UnexpectedValueException::class, 'Value must be an iterable');
    }

    /**
     * @param mixed $value
     */
    static public function isResource($value): void
    {
        if (is_resource($value)) {
            return;
        }

        self::throwException(__METHOD__, UnexpectedValueException::class, 'Value must be a resource');
    }

    /**
     * @param mixed $object
     * @param string $parentClass
     */
    static public function isSubClassOf($object, string $parentClass): void
    {
        if ((is_string($object) || is_object($object)) && is_subclass_of($object, $parentClass, true)) {
            return;
        }

        self::throwException(__METHOD__, UnexpectedValueException::class, 'Object must be a subclass of ' . $parentClass);
    }

    /**
     * @param mixed $value
     */
    static public function isNotFalse($value): void
    {
        if ($value !== false) {
            return;
        }

        self::throwException(__METHOD__, InvalidArgumentException::class, 'Value must not be false');
    }

    /**
     * @param mixed $value
     */
    static public function isNotTrue($value): void
    {
        if ($value !== true) {
            return;
        }

        self::throwException(__METHOD__, InvalidArgumentException::class, 'Value must not be true');
    }
}