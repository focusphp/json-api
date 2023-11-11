# Focus JSON:API

[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%208.2-8892BF.svg?style=flat)](https://php.net/)
[![Latest Stable Version](http://img.shields.io/packagist/v/focus/json-api.svg?style=flat)](https://packagist.org/packages/focus/json-api)
[![CI Status](https://github.com/focusphp/data/actions/workflows/ci.yml/badge.svg?branch=main&event=push)](https://github.com/focusphp/data/actions)
[![Code Coverage](https://codecov.io/gh/focusphp/data/graph/badge.svg?token=XFMRWA70FN)](https://codecov.io/gh/focusphp/data)

A collection of tools for working with [JSON:API](https://jsonapi.org/) data.
This package is an extension of [focus/data](https://github.com/focusphp/data).

## Installation

The best way to install and use this package is with [composer](https://getcomposer.org/):

```shell
composer require focus/json-api
```

## Basic Usage

This package provides some structure to reading JSON:API formatted data.
Both resource and resource collections are supported.

The typical entry point will be `DocumentData`:

```php
use Focus\JsonApi\DocumentData;

$data = DocumentData::fromRequest($request);
```

_**Note:** Like [`JsonData`][focus-json], this package supports reading JSON from
strings and [PSR-7][psr7] request and response objects._

[focus-json]: https://github.com/focusphp/data#json-data
[psr7]: https://github.com/focusphp/data#json-data

Once the document is created, the primary data can be accessed as a resource:

```php
$book = $data->resource();

var_dump($book->attribute(path: 'title'));
var_dump($book->attribute(path: 'subtitle'));
var_dump($book->relation(name: 'author'));
```

Or a collection of resources:

```php
$books = $data->collection();

var_dump($books->ids());
```

Or the included resources:

```php
$publishers = $data->included(type: 'publisher');

var_dump($publishers->ids());
```

## Types

### Identifiers

The `Identifier` object extends [`Data`][focus-data] and adds helper methods to read
the type and identifier value:

```php
$id = $data->id();
$type = $data->type();
```

[focus-data]: https://github.com/focusphp/data

### Resources

The `Resource` object extends from `Identifier` and adds helper methods to read
attributes and relationships:

```php
$id = $data->id();
$topics = $data->attribute(path: 'topics');
$author = $data->relation(name: 'author');
$publishers = $data->relations(name: 'publishers');
```

- The value of `relation()` is an identifier of a to-one relationship. When the
  relationship is undefined, `null` will be returned. When the relationship is defined
  as `null`, a blank `Identifier` will be returned.
- The value of `relations()` is an identifier collection of a to-many relationship.
  When the relationship is undefined, `null` will be returned. When the relationship
  is defined as `null`, an empty `IdentifierCollection` will be returned.

The return values of `relation()` and `relations()` are structured this way to allow
determining when a relationship is not present (undefined) versus being unset (null).

### Identifier Collections

The `IdentifierCollection` object represents a collection of `Identifier` objects
and adds helper methods to read the identifier values:

```php
var_dump($publishers->ids());
```

The collection can also be iterated:

```php
foreach ($publishers as $publisher) {
    var_dump($publisher->id());
    var_dump($publisher->type());
}
```

### Resource Collections

The `ResourceCollection` object represents a collection of `Resource` objects
and adds helper methods to read the identifier values:

```php
var_dump($publishers->ids());
```

The collection can also be iterated:

```php
foreach ($publishers as $publisher) {
    var_dump($publisher->id());
    var_dump($publisher->attribute(path: 'name'));
}
```
