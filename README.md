# Laravel support for spatie/enum

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/laravel-enum.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-enum)
![GitHub Workflow Status](https://img.shields.io/github/workflow/status/spatie/laravel-enum/run-tests?label=tests)
[![Quality Score](https://img.shields.io/scrutinizer/g/spatie/laravel-enum.svg?style=flat-square)](https://scrutinizer-ci.com/g/spatie/laravel-enum)
[![Code Coverage](https://img.shields.io/coveralls/github/spatie/laravel-enum.svg?style=flat-square)](https://coveralls.io/github/spatie/laravel-enum)
[![StyleCI](https://github.styleci.io/repos/180570906/shield?branch=master)](https://github.styleci.io/repos/180570906)
[![Total Downloads](https://img.shields.io/packagist/dt/spatie/laravel-enum.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-enum)

This package provides extended support for our [spatie/enum](https://github.com/spatie/enum) package in Laravel.

## Installation

You can install the package via composer:

```bash
composer require spatie/laravel-enum
```

## Usage

### Casting

Chances are that if you're working in a Laravel project, you'll want to use enums within your models.
This package provides a trait you can use in these models, 
to allow allow automatic casting between stored enum values and enum objects. 

```php
use Spatie\Enum\HasEnums;

class TestModel extends Model
{
    use HasEnums;

    protected $enums = [
        'status' => TestModelStatus::class,
    ];
}
```

You can also define enum as nullable:

```php
protected $enums = [
    'status' => TestModelStatus::class.':nullable',
];
```

By using the `HasEnums` trait, you'll be able to work with the `status` field like so:

```php
$model = TestModel::create([
    'status' => StatusEnum::DRAFT(),
]);
```

You can set the value of an enum field with its textual value:

```php
$model->status = 'published';
```

This can be useful when filling data from a validated request:

```php
$model->fill($request->validated());

// …

$model->status = StatusEnum::PUBLISHED();

// …

$model->status->isEqual(StatusEnum::ARCHIVED());
``` 

In some cases, enums should be stored as integer (index) in the database.

By using the `$casts` property you can cast your `status` attribute to `int` or `integer` and the trait will store the index instead of the value.

### Roadmap

- [ ] Models fields
    - [x] Automatic value casting [#1](https://github.com/spatie/laravel-enum/pull/1)
    - [x] Mapping support [spatie/enum#25](https://github.com/spatie/enum/pull/25)
- [ ] Model scopes [#4](https://github.com/spatie/laravel-enum/pull/4)
- [ ] Validation [#5](https://github.com/spatie/laravel-enum/issues/5)
- [ ] Request Transformation [#7](https://github.com/spatie/laravel-enum/pull/7)

### Scopes

The `HasEnums` trait also provides some useful scopes to query your database.
These scopes will also take the optional mapping you provided into account.

```php
Post::whereEnum('status', StatusEnum::DRAFT());

Post::whereNotEnum('status', StatusEnum::PUBLISHED());
```

You may provide multiple enums as an array:

```php
Post::whereEnum('status', [StatusEnum::DRAFT(), StatusEnum::ARCHIVED()]);

Post::whereNotEnum('status', [StatusEnum::PUBLISHED()]);
```

You may also provide textual input:

```php
Post::whereEnum('status', 'archived');
Post::whereEnum('status', 'legacy archived value');
```

### Testing

``` bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email freek@spatie.be instead of using the issue tracker.

## Postcardware

You're free to use this package, but if it makes it to your production environment we highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using.

Our address is: Spatie, Samberstraat 69D, 2060 Antwerp, Belgium.

We publish all received postcards [on our company website](https://spatie.be/en/opensource/postcards).

## Credits

- [Brent Roose](https://github.com/brendt)
- [All Contributors](../../contributors)

## Support us

Spatie is a webdesign agency based in Antwerp, Belgium. You'll find an overview of all our open source projects [on our website](https://spatie.be/opensource).

Does your business depend on our contributions? Reach out and support us on [Patreon](https://www.patreon.com/spatie). 
All pledges will be dedicated to allocating workforce on maintenance and new awesome stuff.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
