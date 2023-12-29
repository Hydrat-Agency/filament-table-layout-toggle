# Filament plugin adding a toggle button to tables, allowing user to switch between Grid and Table layouts.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/hydrat/filament-table-layout-toggle.svg?style=flat-square)](https://packagist.org/packages/hydrat/filament-table-layout-toggle)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/hydrat/filament-table-layout-toggle/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/hydrat/filament-table-layout-toggle/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/hydrat/filament-table-layout-toggle/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/hydrat/filament-table-layout-toggle/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/hydrat/filament-table-layout-toggle.svg?style=flat-square)](https://packagist.org/packages/hydrat/filament-table-layout-toggle)



This package brings a simple toggle button to Filament tables, allowing users to switch between Grid and Table layouts. This is solution allow mobile users to benefit from the Grid layout, while desktop users can still use the Table layout, without losing the table headers.

Big shoutout to [awcodes/filament-curator](https://github.com/awcodes/filament-curator), which implemented the toogle feature first. This package is simply an extraction of the feature, so it can be used in any project.

## Installation

You can install the package via composer:

```bash
composer require hydrat/filament-table-layout-toggle
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="filament-table-layout-toggle-views"
```

## Usage

First, register the plugin on your Filament panel :

```php
use Hydrat\TableLayoutToggle\TableLayoutTogglePlugin;

->plugins([
  TableLayoutTogglePlugin::make()
      ->persistLayoutInLocalStorage(true) // allow user to keep his layout preference in his local storage
      ->shareLayoutBetweenPages(false) // allow all tables to share the layout option, works only if persistLayoutInLocalStorage is true
      ->displayToggleAction() // used to display the toogle button automatically, on the desired filament hook
      ->listLayoutButtonIcon('heroicon-o-list-bullet')
      ->gridLayoutButtonIcon('heroicon-o-squares-2x2'),
])
```

Then, on page where you are displaying the table, you can use the following trait :

```php
use Hydrat\TableLayoutToggle\Concerns\HasToggleableTable;

class MyListRecords extends ListRecords
{
  use HasToggleableTable;
}
```

Finally, you need to configure your table so it sets the schema based on the selected layout. This is typically done on the resource's `table()` method :

```php
public static function table(Table $table): Table
{
    $livewire = $table->getLivewire();

    return $table
        ->columns(
            $livewire->isGridLayout()
                ? static::getGridTableColumns()
                : static::getTableColumns(),
        )
        ->contentGrid(function () use ($livewire) {
            if ($livewire->isGridLayout() === 'grid') {
                return [
                    'md' => 2,
                    'lg' => 3,
                    'xl' => 4,
                ];
            }

            return null;
        });
}
```

If you rather use your own action or decide where it's displayed, you can first disable the automatic rendering of the toggle button :

```php
->plugins([
  TableLayoutTogglePlugin::make()
      ->displayToggleAction(false),
])
```

Then, you can make use of the helper to generate the base Action or Table action :

```php
use Hydrat\TableLayoutToggle\Facades\TableLayoutToggle;

// Display action on top of the table :
return $table
    ->columns(...)
    ->headerActions([
        TableLayoutToggle::getToggleViewTableAction(compact: true),
    ])

// As page header action :
protected function getHeaderActions(): array
{
    return [
        TableLayoutToggle::getToggleViewAction(compact: false)
            ->hiddenLabel(false)
            ->label('Toggle layout'),
    ];
}

```

```php
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
