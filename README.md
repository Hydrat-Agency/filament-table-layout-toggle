# Filament plugin adding a toggle button to tables, allowing users to switch between Grid and Table layouts.

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Latest Version on Packagist](https://img.shields.io/packagist/v/hydrat/filament-table-layout-toggle.svg?style=flat-square)](https://packagist.org/packages/hydrat/filament-table-layout-toggle)
[![Total Downloads](https://img.shields.io/packagist/dt/hydrat/filament-table-layout-toggle.svg?style=flat-square)](https://packagist.org/packages/hydrat/filament-table-layout-toggle)


This package brings a simple toggle button to Filament tables, allowing end users to switch between Grid and Table layouts on tables. This approach allows mobile users to benefit from the Grid layout, while desktop users can still benefit from Table layout features, such as the table headers, sorting and so on.

> Big shoutout to [awcodes/filament-curator](https://github.com/awcodes/filament-curator), which implemented the toggle feature first on their package. This package is mainly an extraction of the feature so that it can be used in any project, and some other adding such as saving the selected layout in the cache or local storage.

**Migrating from 1.x to 2.x ?** : Please read the [migration guide](MIGRATING.md).


- [Screenshots](#screenshots)
- [Installation](#installation)
- [Usage](#usage)
  - [Panels](#usage_panels)
  - [Standalone tables](#usage_standalone_tables)
- [Configuration](#configuration)
  - [Layout persister](#configuration_persister)
  - [Per-table settings](#configuration_per_table_settings)
  - [Using own action](#configuration_own_action)
- [Changelog](#changelog)
- [Migration guide](#migration_guide)
- [Contributing](#contributing)
- [Security](#security)
- [Credits](#credits)
- [License](#license)


<a name="screenshots"></a>

## Screenshots

**Video capture**

https://github.com/Hydrat-Agency/filament-table-layout-toggle/assets/11785727/b177a0fd-d263-4054-a05f-e6a597554d0f

**Screen capture**

![screenshot_table](https://github.com/Hydrat-Agency/filament-table-layout-toggle/assets/11785727/72035a42-4439-4317-9266-e4a6cd1a757a)
![screenshot_grid](https://github.com/Hydrat-Agency/filament-table-layout-toggle/assets/11785727/56d0ecc8-07b9-459a-b045-c5916adfa703)



<a name="usage_auth_multi_account"></a>

## installation

You can install the package via composer:

```bash
composer require hydrat/filament-table-layout-toggle
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="table-layout-toggle-views"
```

If you are using this package outside of filament panels (standalone tables), you should publish the configuration file :

```bash
php artisan vendor:publish --tag="table-layout-toggle-config"
```

If using panels, this configuration file **WILL NOT** be read by the plugin, as the configuration happens on the plugin registration itself.


<a name="usage"></a>

## Usage

Please chose the appropriate section for your use case (Panels or Standalone tables).


<a name="usage_panels"></a>

### Panels

First, register the plugin on your Filament panel :

```php
use Hydrat\TableLayoutToggle\TableLayoutTogglePlugin;
use Hydrat\TableLayoutToggle\Persisters;

public function panel(Panel $panel): Panel
{
    return $panel
        ->plugins([
            TableLayoutTogglePlugin::make()
                ->setDefaultLayout('grid') // default layout for user seeing the table for the first time
                ->persistLayoutUsing(
                    persister: Persisters\LocalStoragePersister::class, // chose a persister to save the layout preference of the user
                    cacheStore: 'redis', // optional, change the cache store for the Cache persister
                    cacheTtl: 60 * 24, // optional, change the cache time for the Cache persister
                )
                ->shareLayoutBetweenPages(false) // allow all tables to share the layout option for this user
                ->displayToggleAction() // used to display the toggle action button automatically
                ->toggleActionHook('tables::toolbar.search.after') // chose the Filament view hook to render the button on
                ->listLayoutButtonIcon('heroicon-o-list-bullet')
                ->gridLayoutButtonIcon('heroicon-o-squares-2x2'),
        ]);
}
```

Read more about the Layout persister and the configuration options in the [Configuration](#configuration) section.

> Please note that all those configurations are optional, and have default values, which means you can omit them if you don't need to change the default behavior.

Then, on the component containing the table (ListRecord, ManageRelatedRecords, ...), you can use the `HasToggleableTable` trait :

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
                : static::getListTableColumns()
        )
        ->contentGrid(
            fn () => $livewire->isListLayout()
                ? null
                : [
                    'md' => 2,
                    'lg' => 3,
                    'xl' => 4,
                ]
        );
}

// Define the columns for the table when displayed in list layout
public static function getListTableColumns(): array;

// Define the columns for the table when displayed in grid layout
public static function getGridTableColumns(): array;
```

Please note that you must use the Layout tools described in the [filament documentation](https://filamentphp.com/docs/3.x/tables/layout#controlling-column-width-using-a-grid) in order for your Grid layout to render correctly. You may also use the `description()` method to print labels above your values.

```php
public static function getGridTableColumns(): array
{
    return [
        // Make sure to stack your columns together
        Tables\Columns\Layout\Stack::make([

            Tables\Columns\TextColumn::make('status')->badge(),

            // You may group columns together using the Split layout, so they are displayed side by side
            Tables\Columns\Layout\Split::make([
                Tables\Columns\TextColumn::make('customer')
                    ->description(__('Customer'), position: 'above')
                    ->searchable(),

                Tables\Columns\TextColumn::make('owner.name')
                    ->description(__('Owner'), position: 'above')
                    ->searchable(),
            ]),

        ])->space(3)->extraAttributes([
            'class' => 'pb-2',
        ]),
    ];
}
```


<a name="usage_standalone_tables"></a>

### Standalone tables

You can manage the plugin settings via the published configuration file.
The options are self-documented, and should be pretty straightforward to use.

Then, on the component containing the table, you can use the `HasToggleableTable` trait :

```php
namespace App\Livewire\Users;

use Hydrat\TableLayoutToggle\Concerns\HasToggleableTable;

class ListUsers extends Component implements HasForms, HasTable, HasActions
{
    use InteractsWithTable;
    use InteractsWithActions;
    use InteractsWithForms;
    use HasToggleableTable; // <-- Add this line
}
```

If you plan to persist the layout in the local storage, you must also change your view to include the needed assets :

```blade
[...]
{{ $this->table }}

{{ $this->renderLayoutViewPersister() }} <-- Add this line
```

Finally, you need to configure your table so it dynamically sets the schema based on the selected layout. This is typically done on the component's `table()` method :

```php
public function table(Table $table): Table
{
    return $table
        ->columns(
            $this->isGridLayout()
                ? $this->getGridTableColumns()
                : $this->getListTableColumns()
        )
        ->contentGrid(
            fn () => $this->isListLayout()
                ? null
                : [
                    'md' => 2,
                    'lg' => 3,
                    'xl' => 4,
                ]
        );
}

// Define the columns for the table when displayed in list layout
public static function getListTableColumns(): array;

// Define the columns for the table when displayed in grid layout
public static function getGridTableColumns(): array;
```

Please note that you must use the Layout tools described in the [filament documentation](https://filamentphp.com/docs/3.x/tables/layout#controlling-column-width-using-a-grid) in order for your Grid layout to render correctly. You may also use the `description()` method to print labels above your values.

```php
public static function getGridTableColumns(): array
{
    return [
        // Make sure to stack your columns together
        Tables\Columns\Layout\Stack::make([

            Tables\Columns\TextColumn::make('status')->badge(),

            // You may group columns together using the Split layout, so they are displayed side by side
            Tables\Columns\Layout\Split::make([
                Tables\Columns\TextColumn::make('customer')
                    ->description(__('Customer'), position: 'above')
                    ->searchable(),

                Tables\Columns\TextColumn::make('owner.name')
                    ->description(__('Owner'), position: 'above')
                    ->searchable(),
            ]),

        ])->space(3)->extraAttributes([
            'class' => 'pb-2',
        ]),
    ];
}
```


<a name="configuration"></a>

## Configuration

<a name="configuration_persister"></a>

### Layout persister

The plugin proposes several persister classes to save the layout preference of the user :

```php
Persisters\LocalStoragePersister::class  # Save the layout in the local storage
Persisters\CachePersister::class         # Save the layout in the application cache
Persisters\DisabledPersister::class      # Do not persist the layout
```

The cache persister has several options, which you can toggle using the `persistLayoutUsing` method if using the Plugin in panels, or by changing the configuration file if using standalone tables.

```php
// Plugin registration
TableLayoutTogglePlugin::make()
    ->persistLayoutUsing(
        persister: Persisters\CachePersister::class,
        cacheStore: 'redis', // change storage to redis
        cacheTtl: 60 * 24 * 7 * 4, // change ttl to 1 month
    );

// Configuration file
'persiter' => Persisters\CachePersister::class,

'cache' => [
    'storage' => 'redis', // change storage to redis

    'time' => 60 * 24 * 7 * 4, // change ttl to 1 month
],
```

You can also create your own persister by implementing the  the `LayoutPersister` contract.
The `AbstractPersister` class provides some default implementation which can be overridden if needed.

```php
<?php

namespace App\Filament\Persisters;

use Hydrat\TableLayoutToggle\Persisters;
use Hydrat\TableLayoutToggle\Contracts\LayoutPersister;

class CustomPersister extends AbstractPersister implements LayoutPersister
{
    public function setState(string $layoutState): self
    {
        persisterSetState($this->getKey(), $layoutState);

        return $this;
    }

    public function getState(): ?string
    {
        return persisterGetState($this->getKey());
    }

    public function onComponentBoot(): void
    {
        // add filament hooks to render a custom view or so...
    }
}
```

You can access the Livewire component using `$this->component`.


<a name="configuration_per_table_settings"></a>

### Change settings per-table

You may need to tweak some settings for a specific table only. This can be done by overriding the default methods provided by this package in the component you are using the `HasToggleableTable` trait in :

```php
namespace App\Livewire\Users;

class ListUsers extends Component implements HasForms, HasTable, HasActions
{
    use HasToggleableTable;

    /**
     * Set the default layout for this table, when the user sees it for the first time.
     */
    public function getDefaultLayoutView(): string
    {
        return 'grid';
    }

    /**
     * Modify the persister configuration,
     * or initialize a new one for this component.
     */
    public function configurePersister(): void
    {
        // customize the persister for this specific table
        $this->layoutPersister
            ->setKey('custom_key'. auth()->id())
            ->setCacheDriver('redis')
            ->setExpiration(60 * 24 * 7 * 4);

        // or create a new persister for this specific table
        $this->layoutPersister = new CustomPersister($this);
    }
}
```


<a name="configuration_own_action"></a>

### Using own action

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

<a name="changelog"></a>

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

<a name="migration_guide"></a>

## Migration guide

Please see [MIGRATION](MIGRATION.md) for more information on what has changed recently.

<a name="contributing"></a>

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.


<a name="security"></a>

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.


<a name="credits"></a>

## Credits

- [Thomas](https://github.com/Hydrat)
- [All Contributors](../../contributors)


<a name="license"></a>

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
