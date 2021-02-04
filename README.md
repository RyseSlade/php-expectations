# Aedon PHP Expectations

[![GitHub release](https://img.shields.io/github/v/release/RyseSlade/php-expectations.svg)](https://github.com/RyseSlade/php-expectations/releases/)
[![Build Status](https://travis-ci.org/RyseSlade/php-expectations.svg?branch=master)](https://travis-ci.org/RyseSlade/php-expectations)
[![Mutation testing badge](https://img.shields.io/endpoint?style=flat&url=https%3A%2F%2Fbadge-api.stryker-mutator.io%2Fgithub.com%2FRyseSlade%2Fphp-expectations%2Fmaster)](https://dashboard.stryker-mutator.io/reports/github.com/RyseSlade/php-expectations/master)
[![GitHub license](https://img.shields.io/badge/license-MIT-green)](https://github.com/RyseSlade/php-expectations/blob/master/LICENSE)

Reduce lines of code with this expectations library. Use expectations for conditional exceptions.

### Requirements

PHP 7.4+

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
