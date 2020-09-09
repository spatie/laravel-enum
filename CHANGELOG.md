# Changelog

All notable changes to `laravel-enum` will be documented in this file

## 1.6.1 - 2020-09-09

- Support for Laravel 8

## 1.6.0 - 2020-04-30

- add array of enums cast [#43](https://github.com/spatie/laravel-enum/pull/43)

## 1.5.0 - 2020-04-17

- add classname to make command output [#41](https://github.com/spatie/laravel-enum/pull/41)

## 1.4.0 - 2020-03-11

- add request transformer [#7](https://github.com/spatie/laravel-enum/pull/7)
  - form request: `Spatie\Enum\Laravel\Http\Requests\TransformsEnums`
  - middleware: `Spatie\Enum\Laravel\Http\Middleware\TransformEnums`
  - macro: `Illuminate\Http\Request::transformEnums()`

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
