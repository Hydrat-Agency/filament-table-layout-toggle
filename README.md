# Filament plugin adding a toggle button to tables, allowing user to switch between Grid and Table layouts.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/hydrat/filament-table-layout-toggle.svg?style=flat-square)](https://packagist.org/packages/hydrat/filament-table-layout-toggle)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/hydrat/filament-table-layout-toggle/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/hydrat/filament-table-layout-toggle/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/hydrat/filament-table-layout-toggle/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/hydrat/filament-table-layout-toggle/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/hydrat/filament-table-layout-toggle.svg?style=flat-square)](https://packagist.org/packages/hydrat/filament-table-layout-toggle)



This package brings a simple toggle button to Filament tables, allowing users to switch between Grid and Table layouts. This is solution allow mobile users to benefit from the Grid layout, while desktop users can still use the Table layout, without losing the table headers.

Big shoutout to [awcodes/filament-curator](https://github.com/awcodes/filament-curator), which implemented the toogle feature first. This package is simply an extraction of the feature, so it can be used in any project.



https://github.com/Hydrat-Agency/filament-table-layout-toggle/assets/11785727/b177a0fd-d263-4054-a05f-e6a597554d0f



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

public function panel(Panel $panel): Panel
{
    return $panel
        ->plugins([
            TableLayoutTogglePlugin::make()
                ->persistLayoutInLocalStorage(true) // allow user to keep his layout preference in his local storage
                ->shareLayoutBetweenPages(false) // allow all tables to share the layout option (requires persistLayoutInLocalStorage to be true)
                ->displayToggleAction() // used to display the toogle button automatically, on the desired filament hook (defaults to table bar)
                ->listLayoutButtonIcon('heroicon-o-list-bullet')
                ->gridLayoutButtonIcon('heroicon-o-squares-2x2'),
        ]);
}
```

Then, on the component containing the table (ListRecord, ManageRelatedRecords, ...), you can use the following trait :

```php
use Hydrat\TableLayoutToggle\Concerns\HasToggleableTable;

class MyListRecords extends ListRecords
{
    use HasToggleableTable;
}
```

Finally, you need to configure your table so it dynamically sets the schema based on the selected layout. This is typically done on the resource's `table()` method :

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

public static function getGridTableColumns(): array;
public static function getTableColumns(): array;
```

If you rather use your own action, you should first disable the automatic rendering of the toggle button on the plugin registration :

```php
$panel
    ->plugins([
      TableLayoutTogglePlugin::make()
          ->displayToggleAction(false),
    ])
```

Then, get base `Action` or `TableAction` from the provided helper :

```php
use Hydrat\TableLayoutToggle\Facades\TableLayoutToggle;

// Display action on top of the table :
return $table
    ->columns(...)
    ->headerActions([
        TableLayoutToggle::getToggleViewTableAction(compact: true),
    ]);

// As Filament page header action :
protected function getHeaderActions(): array
{
    return [
        TableLayoutToggle::getToggleViewAction(compact: false)
            ->hiddenLabel(false)
            ->label('Toggle layout'),
    ];
}

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
