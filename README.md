[<img src="https://github-ads.s3.eu-central-1.amazonaws.com/support-ukraine.svg?t=1" />](https://supportukrainenow.org)

# Laravel support for spatie/enum

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/laravel-enum.svg?style=for-the-badge)](https://packagist.org/packages/spatie/laravel-enum)
[![License](https://img.shields.io/github/license/spatie/laravel-enum?style=for-the-badge)](https://github.com/spatie/laravel-enum/blob/master/LICENSE.md)
![Postcardware](https://img.shields.io/badge/Postcardware-%F0%9F%92%8C-197593?style=for-the-badge)

[![PHP from Packagist](https://img.shields.io/packagist/php-v/spatie/laravel-enum?style=flat-square)](https://packagist.org/packages/spatie/laravel-enum)
[![Build Status](https://img.shields.io/github/workflow/status/spatie/laravel-enum/run-tests?label=tests&style=flat-square)](https://github.com/spatie/laravel-enum/actions?query=workflow%3Arun-tests)
[![Total Downloads](https://img.shields.io/packagist/dt/spatie/laravel-enum.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-enum)

This package provides extended support for our [spatie/enum](https://github.com/spatie/enum) package in Laravel.

## Installation

You can install the package via composer:

```bash
composer require spatie/laravel-enum
```

## Support us

[<img src="https://github-ads.s3.eu-central-1.amazonaws.com/laravel-enum.jpg?t=1" width="419px" />](https://spatie.be/github-ad-click/laravel-enum)

We invest a lot of resources into creating [best in class open source packages](https://spatie.be/open-source). You can support us by [buying one of our paid products](https://spatie.be/open-source/support-us).

We highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using. You'll find our address on [our contact page](https://spatie.be/about-us). We publish all received postcards on [our virtual postcard wall](https://spatie.be/open-source/postcards).

## Usage

```php
// a Laravel specific base class
use Spatie\Enum\Laravel\Enum;

/**
 * @method static self DRAFT()
 * @method static self PREVIEW()
 * @method static self PUBLISHED()
 * @method static self ARCHIVED()
 */
final class StatusEnum extends Enum {}
```

### Model Attribute casting

Chances are that if you're working in a Laravel project, you'll want to use enums within your models.
This package provides two custom casts and the `\Spatie\Enum\Laravel\Enum` also implements the `\Illuminate\Contracts\Database\Eloquent\Castable` interface.

```php
use Illuminate\Database\Eloquent\Model;

class TestModel extends Model
{
    protected $casts = [
        'status' => StatusEnum::class,
        'nullable_enum' => StatusEnum::class.':nullable',
        'array_of_enums' => StatusEnum::class.':collection',
        'nullable_array_of_enums' => StatusEnum::class.':collection,nullable',
    ];
}
```

By using the casts the casted attribute will always be an instance of the given enum.

```php
$model = new TestModel();
$model->status = StatusEnum::DRAFT();
$model->status->equals(StatusEnum::DRAFT());
```

### Validation Rule

This package provides a validation rule to validate your request data against a given enumerable.

```php
use Spatie\Enum\Laravel\Rules\EnumRule;

$rules = [
    'status' => new EnumRule(StatusEnum::class),
];
```

This rule validates that the value of `status` is any possible representation of the `StatusEnum`.

But you can also use the simple string validation rule definition:

```php
$rules = [
    'status' => [
        'enum:'.StatusEnum::class,
    ],
];
```

If you want to customize the failed validation messages you can publish the translation file.

```bash
php artisan vendor:publish --provider="Spatie\Enum\Laravel\EnumServiceProvider" --tag="translation"
```

We pass several replacements to the translation key which you can use.

-   `attribute` - the name of the validated attribute
-   `value` - the actual value that's validated
-   `enum` - the full class name of the wanted enumerable
-   `other` - a comma separated list of all possible values - they are translated via the `enums` array in the translation file

### Request Data Transformation

A common scenario is that you receive an enumerable value as part of your request data.
To let you work with it as a real enum object you can transform request data to an enum in a similar way to the model attribute casting.

#### Request macro

There is a request macro available which is the base for the other possible ways to cast request data to an enumerable.

```php
$request->transformEnums($enumCastRules);
```

This is an example definition of all possible request enum castings.
There are three predefined keys available as constants on `Spatie\Enum\Laravel\Http\EnumRequest` to cast enums only in specific request data sets.
All other keys will be treated as independent enum casts and are applied to the combined request data set.

```php
use Spatie\Enum\Laravel\Http\EnumRequest;

$enums = [
    // cast the status key independent of it's data set
    'status' => StatusEnum::class,
    // cast the status only in the request query params
    EnumRequest::REQUEST_QUERY => [
        'status' => StatusEnum::class,
    ],
    // cast the status only in the request post data
    EnumRequest::REQUEST_REQUEST => [
        'status' => StatusEnum::class,
    ],
    // cast the status only in the request route params
    EnumRequest::REQUEST_ROUTE => [
        'status' => StatusEnum::class,
    ],
];
```

You can call this macro yourself in every part of your code with access to a request instance.
Most commonly you will do this in your controller action if you don't want to use one of the other two ways.

#### Form Requests

Form requests are the easiest way to cast the data to an enum.

```php
use Illuminate\Foundation\Http\FormRequest;
use Spatie\Enum\Laravel\Http\Requests\TransformsEnums;
use Spatie\Enum\Laravel\Rules\EnumRule;

class StatusFormRequest extends FormRequest
{
    use TransformsEnums;

    public function rules(): array
    {
        return [
            'status' => new EnumRule(StatusEnum::class),
            'properties.level' => new EnumRule(LevelEnum::class),
        ];
    }

    public function enums(): array
    {
        return [
            'status' => StatusEnum::class,
            'properties.level' => LevelEnum::class,
        ];
    }
}
```

The request data transformation is done after validation via the `FormRequest::passedValidation()` method. If you define your own `passedValidation()` method you have to call the request macro `transformEnums()` yourself.

```php
protected function passedValidation()
{
    $this->transformEnums($this->enums());

    // ...
}
```

### Route Binding

Beside using form requests, you can also use route binding. Similar [Laravel's Route Model Binding](https://laravel.com/docs/routing#route-model-binding), it automatically inject enum instances into your route action.

#### Implicit Binding

To use implicit route binding, be sure add `Spatie\Enum\Laravel\Http\Middleware\SubstituteBindings` middleware. For example, add it in your `app\Http\Kernel.php`:

```php
protected $middlewareGroups = [
    'web' => [
        // ...
        \Spatie\Enum\Laravel\Http\Middleware\SubstituteEnumBindings::class,
    ],
];
```

Use a type-hinted variable name that matches route segment to use implicit route binding.

```php
Route::get('/posts/{status}', function (StatusEnum $status) {
    return $status;
});
```

#### Explicit Binding

To have an explicit binding, there is a `Route::enum()` macro.
It's important that your route/group uses the `\Illuminate\Routing\Middleware\SubstituteBindings` middleware.
This middleware is enabled by default for the `web` route group.

```php
Route::enum('status', StatusEnum::class);
Route::get('/posts/{status}', function (Request $request) {
    return $request->route('status');
});
```

### Enum Make Command

We provide an artisan make command which allows you to quickly create new enumerables.

```bash
php artisan make:spatie-enum StatusEnum
```

You can use `--method` option to predefine some enum values - you can use them several times.

### Faker Provider

It's very likely that you will have a model with an enum attribute and you want to generate random enum values in your model factory.
Because doing so with default [faker](https://github.com/fzaninotto/Faker) is a lot of copy'n'paste we've got you covered with a faker provider `Spatie\Enum\Laravel\Faker\FakerEnumProvider`.
The static `register()` method is only a little helper - you can for sure register the provider the default way `$faker->addProvider(new FakerEnumProvider)`.

The faker methods itself are inherited from the base packages [Faker Provider](https://github.com/spatie/enum#faker-provider).

### Livewire

You can use an enum as a property on a Livewire component like this:

```php
class ShowCustomer extends Component
{
    public StatusEnum $statusEnum;

    public function mount($id)
    {
        $customer = Customer::find($id);
        $this->statusEnum = $customer->status;
    }

    public function render()
    {
        return view('livewire.customer');
    }
}
```

Just one thing is required to make this work: implement `\Livewire\Wireable` on all the enums you’ll be using with Livewire::

```php
use Livewire\Wireable;

/**
 * @method static self pending()
 * @method static self active()
 */
final class StatusEnum implements Wireable
{}
```

## Testing

```bash
composer test
composer test-coverage
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](https://github.com/spatie/.github/blob/main/CONTRIBUTING.md) for details.

### Security

If you've found a bug regarding security please mail [security@spatie.be](mailto:security@spatie.be) instead of using the issue tracker.

## Credits

-   [Brent Roose](https://github.com/brendt)
-   [Tom Witkowski](https://github.com/Gummibeer)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
