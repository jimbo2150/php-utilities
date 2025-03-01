![Version](https://img.shields.io/github/v/release/jimbo2150/php-utilities)
![License](https://img.shields.io/github/license/jimbo2150/php-utilities)
![PHP Required Version](https://img.shields.io/packagist/dependency-v/jimbo2150/php-utilities/php)

# PHP Utilities

**A collection of useful PHP utility functions, interfaces, classes, traits, and enums to simplify common tasks and enhance your PHP projects.**

## Table of Contents

-   [Installation](#installation)
-   [Usage](#usage)
-   [Features](#features)
-   [Changelog](#changelog)

## Installation

This library is distributed via [Composer](https://getcomposer.org/)'s [Packagist](https://packagist.org/) repository. To install it, run the following command in your project directory:

```bash
composer require jimbo2150/php-utilities
```

## Usage

### Traitable

The `Traitable` trait provides a `hasTrait($traitName)` method that checks if the given trait is associated with the object instance. It works similar to how `instanceof` works with class hierarchy. It checks if the trait is directly connected to the class or if any of the class's ancestors have the given trait or if the class or class ancestors' traits uses the checked trait (e.g. it checks the trait hierarchy). It also caches the trait hierarchy as it traverses the tree so subsequent runs with classes that have those traits will perform faster and not have to traverse all or part of the hierarchy.

Here is an example of usage:

```php
use Jimbo2150\PhpUtilities\Traitable;

trait resourceful {

}

trait streamable {
	// ...
}

trait writeable {
	use resourceful;
	use streamable;
	// ...
}

trait readable {
	use resourceful;
	use streamable;
	// ...
}

trait blankable {

}

class StringBuffer {
	use Traitable;
	use writeable;
	use readable;
	// ...
}

$string_stream = new StringBuffer();
$string_stream->hasTrait(resourceful::class); // true
$string_stream->hasTrait(readable::class); // true
$string_stream->hasTrait(blankable::class); // false
```

### Traits

This helper class can be used instead of the `Traitable` trait to check if a class is an instance of a given trait. Using the same class/trait definition as above, you can run:

```php
use Jimbo2150\PhpUtilities\Traits;

$string_stream = new StringBuffer();
Traits::instanceOf($string_stream, resourceful::class); // true
Traits::instanceOf($string_stream, readable::class); // true
Traits::instanceOf($string_stream, blankable::class); // false
```

## Features

Currently this library has a Traitable trait which allows you to check if the instance has an assigned trait to itself or any of it's ancestors (or any of the trait's traits). It's the equivalent of doing `$x instanceof TraitX` to check if an object is an instance of a given trait. There is also an associated `Trait` helper class with static functions which actually perform the trait check.

Feel free to suggest other utilities to add and I will take them under consideration - or submit a pull request with a new utility you would like to have added.

## Changelog

See [CHANGELOG.md](/CHANGELOG.md).