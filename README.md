# Aedon PHP Expectations

[![GitHub release](https://img.shields.io/github/v/release/RyseSlade/php-expectations.svg)](https://github.com/RyseSlade/php-expectations/releases/)
[![Build Status](https://travis-ci.org/RyseSlade/php-expectations.svg?branch=master)](https://travis-ci.org/RyseSlade/php-expectations)
[![Infection MSI](https://badge.stryker-mutator.io/github.com/RyseSlade/php-expectations/master)](https://badge.stryker-mutator.io/github.com/RyseSlade/php-expectations)
[![GitHub license](https://img.shields.io/badge/license-MIT-green)](https://github.com/RyseSlade/php-expectations/blob/master/LICENSE)

Reduce lines of code with this expectations library. Use expectations for conditional exceptions.

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

### Support

Join Discord: https://discord.gg/xhk7dN

Aedon PHP Expectations created by Michael "Striker" Berger
