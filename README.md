# Aedon PHP Expectations

[![GitHub release](https://img.shields.io/github/v/release/RyseSlade/php-expectations.svg)](https://github.com/RyseSlade/php-expectations/releases/)
[![GitHub license](https://img.shields.io/badge/license-MIT-green)](https://github.com/RyseSlade/php-expectations/blob/master/LICENSE)

Reduce lines of code with this expectations library. Use expectations for conditional exceptions.

### Requirements

PHP 8

### Usage examples

#### Basic example

Instead of...
```php
$value = new stdClass();

if (!$value instanceof stdClass) {
    throw new InvalidArgumentException('some message');
}
```
Do it like this...

```php
$value = new stdClass();

\Aedon\Expect::isInstanceOf($value, stdClass::class);
```

#### Custom Exceptions

In case you want to change the exception thrown for specific tests.

```php
\Aedon\Expect::registerCustomException('isTrue', InvalidArgumentException::class);

// This will now throw InvalidArgumentException instead of RuntimeException
\Aedon\Expect::isTrue(false); 
```

This can also be used to execute a callable when an expectation fails.

```php
\Aedon\Expect::registerCustomException('isTrue', function() {
    return false; // omit this if you still want to throw the default exception 
});
```

### Support

Join Discord: https://discord.gg/NEfRerY

Aedon PHP Expectations created by Michael "Striker" Berger
