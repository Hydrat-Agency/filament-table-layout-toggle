# Filament plugin adding a toggle button to tables, allowing users to switch between Grid and Table layouts.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/hydrat/filament-table-layout-toggle.svg?style=flat-square)](https://packagist.org/packages/hydrat/filament-table-layout-toggle)
[![Total Downloads](https://img.shields.io/packagist/dt/hydrat/filament-table-layout-toggle.svg?style=flat-square)](https://packagist.org/packages/hydrat/filament-table-layout-toggle)


This package brings a simple toggle button to Filament tables, allowing end users to switch between Grid and Table layouts on tables. This approach allows mobile users to benefit from the Grid layout, while desktop users can still benefit from Table layout features, such as the table headers, sorting and so on.

> Big shoutout to [awcodes/filament-curator](https://github.com/awcodes/filament-curator), which implemented the toggle feature first on their package. This package is mainly an extraction of the feature so that it can be used in any project, and some other adding such as saving the selected layout in the local storage.


## Screenshots

https://github.com/Hydrat-Agency/filament-table-layout-toggle/assets/11785727/b177a0fd-d263-4054-a05f-e6a597554d0f

![screenshot_table](https://github.com/Hydrat-Agency/filament-table-layout-toggle/assets/11785727/72035a42-4439-4317-9266-e4a6cd1a757a)
![screenshot_grid](https://github.com/Hydrat-Agency/filament-table-layout-toggle/assets/11785727/56d0ecc8-07b9-459a-b045-c5916adfa703)


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
        ->contentGrid(
            fn () => $livewire->isTableLayout()
                ? null
                : [
                    'md' => 2,
                    'lg' => 3,
                    'xl' => 4,
                ];
        );
}

public static function getGridTableColumns(): array;
public static function getTableColumns(): array;
```

If you rather use your own action instead of the default one, you should first disable it on the plugin registration :

```php
$panel
    ->plugins([
      TableLayoutTogglePlugin::make()
          ->displayToggleAction(false),
    ])
```

Then, you can get and extend base `Action` or `TableAction` from the provided helper :

```php
use Hydrat\TableLayoutToggle\Facades\TableLayoutToggle;

# eg: Display action on top of the table :
return $table
    ->columns(...)
    ->headerActions([
        TableLayoutToggle::getToggleViewTableAction(compact: true),
    ]);

# eg: As Filament page header action :
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
