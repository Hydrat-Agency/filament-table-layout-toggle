# Filament plugin adding a toggle button to tables, allowing user to switch between Grid and Table layouts.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/hydrat/filament-table-layout-toggle.svg?style=flat-square)](https://packagist.org/packages/hydrat/filament-table-layout-toggle)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/hydrat/filament-table-layout-toggle/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/hydrat/filament-table-layout-toggle/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/hydrat/filament-table-layout-toggle/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/hydrat/filament-table-layout-toggle/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/hydrat/filament-table-layout-toggle.svg?style=flat-square)](https://packagist.org/packages/hydrat/filament-table-layout-toggle)



This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Installation

You can install the package via composer:

```bash
composer require hydrat/filament-table-layout-toggle
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="filament-table-layout-toggle-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="filament-table-layout-toggle-config"
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="filament-table-layout-toggle-views"
```

This is the contents of the published config file:

```php
return [
];
```

## Usage

```php
$TableLayoutToggle = new Hydrat\TableLayoutToggle();
echo $TableLayoutToggle->echoPhrase('Hello, Hydrat!');
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Thomas](https://github.com/Hydrat)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
