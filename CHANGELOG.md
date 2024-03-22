# Changelog

All notable changes to `laravel-enum` will be documented in this file

## 3.0.3 - 2024-03-22

### What's Changed

* Support Laravel 11 by @justinaskav in https://github.com/spatie/laravel-enum/pull/104

### New Contributors

* @justinaskav made their first contribution in https://github.com/spatie/laravel-enum/pull/104

**Full Changelog**: https://github.com/spatie/laravel-enum/compare/3.0.2...3.0.3

## 3.0.2 - 2023-02-10

- Add Laravel 10 support

## 3.0.0 - 2022-02-07

**Full Changelog**: https://github.com/spatie/laravel-enum/compare/2.5.2...3.0.0

## 3.0.1 - 2022-02-05

- Fix nested form data casting - [#86](https://github.com/spatie/laravel-enum/pull/86)

## 3.0.1 - 2022-02-05

- Fix nested form data casting - [#86](https://github.com/spatie/laravel-enum/pull/86)

## 3.0.0 - 2022-02-03

- Support Laravel 9
- Enum request data transformation is only possible with `$request->input()` and not with `$request->query->get()` anymore:

```diff
- $request->query->get('status')
+ $request->input('status')




```
- If you're working directly with the `InputBag` request object, you'll need to use `all()['']` instead of `get()`:

```diff
- $request->request->get('status');
+ $request->request->all()['status'];




```
## 2.5.2 - 2021-07-24

- fix `:attribute` replacement in `\Spatie\Enum\Laravel\Rules\EnumRule::message()` to respect custom attribute translations - [#77](https://github.com/spatie/laravel-enum/pull/77)

## 2.5.1 - 2021-05-31

- fix `comma` format with empty string value in database - [#73](https://github.com/spatie/laravel-enum/pull/73)
- replaced deprecated `make()` call with `from()` - [#74](https://github.com/spatie/laravel-enum/pull/74)

## 2.5.0 - 2021-05-01

- add `comma` format to `\Spatie\Enum\Laravel\Casts\EnumCollectionCast` - [#70](https://github.com/spatie/laravel-enum/pull/70)
- add `\Spatie\Enum\Laravel\Enum::toRule()` method - [#66](https://github.com/spatie/laravel-enum/pull/66)

## 2.4.0 - 2021-02-19

- add implicit and explicit route binding - [#65](https://github.com/spatie/laravel-enum/pull/65)
- drop unusable `Spatie\Enum\Laravel\Http\Middleware\TransformEnums` middleware in favor of route binding

> **Warning:** the dropped `TransformEnums` middleware was unusable - but if you've found a way to use it in your project this release will be breaking and you will have to switch to form requests or route binding.

## 2.3.0 - 2021-02-12

- accept enum instance in validation rule (Livewire) - [#64](https://github.com/spatie/laravel-enum/pull/64)

## 2.2.0 - 2020-11-24

- add customized enum stub logic - [#58](https://github.com/spatie/laravel-enum/pull/58)

## 2.1.0 - 2020-10-22

- migrate to base packages faker provider - [spatie/enum#74](https://github.com/spatie/enum/pull/74)

## 2.0.1 - 2020-10-03

- fix `\Spatie\Enum\Laravel\Rules\EnumRule` with invalid types passed in - [#54](https://github.com/spatie/laravel-enum/pull/54)

## 2.0.0 - 2020-09-21

- upgrade [spatie/enum](https://github.com/spatie/enum) to *v3* - [spatie/enum#56](https://github.com/spatie/enum/pull/56)
- drop **PHP** support for `7.2` and `7.3`
- drop **Laravel** support for `5.8` and `6.0` and `7.0`
- drop several custom exceptions:
- - `ExpectsArrayOfEnumsField`
  
- 
- 
- - `InvalidEnumError`
  
- 
- 
- - `NoSuchEnumField`
  
- 
- 
- 
- replace `HasEnums` trait by custom casts and default laravel query builder logic
- add custom casts `EnumCast` and `EnumCollectionCast`
- reduce `make:enum` command to `--method` option - no value/label mapping or method name formatting any more
- add Laravel focused base `Enum` class which implements `Jsonable, Castable`
- drop `enum_index|EnumIndexRule`, `enum_name|EnumNameRule` and `enum_value|EnumValueRule` validation rules
- add [Faker](https://github.com/fzaninotto/Faker) provider to generate random enum instances, values and labels `\Spatie\Enum\Laravel\Faker\FakerEnumProvider`

## 1.6.1 - 2020-09-09

- Support for Laravel 8

## 1.6.0 - 2020-04-30

- add array of enums cast [#43](https://github.com/spatie/laravel-enum/pull/43)

## 1.5.0 - 2020-04-17

- add classname to make command output [#41](https://github.com/spatie/laravel-enum/pull/41)

## 1.4.0 - 2020-03-11

- add request transformer [#7](https://github.com/spatie/laravel-enum/pull/7)
- - form request: `Spatie\Enum\Laravel\Http\Requests\TransformsEnums`
  
- 
- 
- - middleware: `Spatie\Enum\Laravel\Http\Middleware\TransformEnums`
  
- 
- 
- - macro: `Illuminate\Http\Request::transformEnums()`
  
- 
- 
- 

## 1.3.0 - 2020-03-02

- add Laravel 7 support [#34](https://github.com/spatie/laravel-enum/pull/34)

## 1.2.0 - 2020-01-16

- add enum validation rules [#13](https://github.com/spatie/laravel-enum/pull/13)

## 1.1.0 - 2020-01-16

- add nullable model attribute enum castings [#25](https://github.com/spatie/laravel-enum/pull/25) / [#29](https://github.com/spatie/laravel-enum/pull/29)

## 1.0.1 - 2020-01-02

- require symfony/console with PHP7.4 compatible version

## 1.0.0 - 2019

- initial release
