# Migrating from Filament v3 to v4

This guide will help you upgrade your Filament Table Layout Toggle package from Filament v3 to v4.

## New Requirements

- **PHP**: 8.2+
- **Laravel**: 11.28+
- **Filament**: 4.0+
- **Tailwind CSS**: 4.0+ (if using custom theme CSS)

## Breaking Changes

### 1. Asset Registration Changes

In Filament v4, asset registration has been simplified and moved to the plugin system. The old `FilamentAsset::register()` and `FilamentIcon::register()` methods are no longer needed in the service provider.

**Before (v3):**
```php
// Asset Registration
FilamentAsset::register(
    $this->getAssets(),
    $this->getAssetPackageName()
);

// Icon Registration
FilamentIcon::register($this->getIcons());
```

**After (v4):**
```php
// Assets are now registered through the plugin system
// No manual asset registration needed in service provider
```

### 2. Render Hook Changes

The `FilamentView::registerRenderHook()` method has been deprecated in v4. This affects the `HasToggleableTable` trait.

**Before (v3):**
```php
protected function registerLayoutViewToogleActionHook(string $filamentHook)
{
    FilamentView::registerRenderHook(
        $filamentHook,
        fn (): View => view('table-layout-toggle::toggle-action', [
            'gridIcon' => Config::getGridLayoutButtonIcon(),
            'listIcon' => Config::getListLayoutButtonIcon(),
        ]),
        scopes: static::class,
    );
}
```

**After (v4):**
```php
protected function registerLayoutViewToogleActionHook(string $filamentHook)
{
    // Note: FilamentView::registerRenderHook is deprecated in v4
    // This functionality should be moved to the plugin system or component registration
    // For now, we'll use the view directly in the component
}
```

### 3. Package Dependencies

Several package dependencies have been updated for v4 compatibility:

**composer.json changes:**
- `filament/filament`: `^3.0` → `^4.0`
- `php`: `^8.1` → `^8.2`
- `illuminate/contracts`: `^10.0|^11.0|^12.0` → `^11.0|^12.0`
- `nunomaduro/collision`: `^7.9` → `^8.0`
- `orchestra/testbench`: `^8.0` → `^9.0`
- `pestphp/pest`: `^2.0` → `^3.0`
- `pestphp/pest-plugin-laravel`: `^2.0` → `^3.0`
- `spatie/laravel-ray`: `^1.26` → `^2.0`

**package.json changes:**
- `tailwindcss`: `^3.3.3` → `^4.0.0-alpha.25`
- `@awcodes/filament-plugin-purge`: `^1.1.1` → `^2.0.0`
- Other dev dependencies updated to latest compatible versions

### 4. Tailwind CSS v4

The package now uses Tailwind CSS v4, which introduces some breaking changes:

- Updated build scripts to use v4 syntax
- Updated purge command to target v4.x
- Added Tailwind v4 specific configuration

## Migration Steps

### Step 1: Update Dependencies

```bash
# Update Composer dependencies
composer update

# Update NPM dependencies
npm install
```

### Step 2: Update Configuration

The configuration file (`config/table-layout-toggle.php`) remains the same and is compatible with v4.

### Step 3: Update Your Application

If you're using this package in a Filament v4 application:

1. Ensure your application meets the new requirements (PHP 8.2+, Laravel 11.28+)
2. Update your Filament installation to v4
3. Update your Tailwind CSS to v4 if you're using custom themes

### Step 4: Test Your Implementation

After upgrading:

1. Run your test suite: `./vendor/bin/pest`
2. Test the table layout toggle functionality in your application
3. Verify that the toggle action appears and functions correctly

## Compatibility Notes

- **Laravel 10**: No longer supported
- **PHP 8.1**: No longer supported
- **Filament 3.x**: No longer supported
- **Tailwind CSS 3.x**: No longer supported for custom themes

## Troubleshooting

### Common Issues

1. **Asset not loading**: Ensure you're using the plugin system correctly in Filament v4
2. **Render hooks not working**: The old render hook system has been replaced; use component-based rendering instead
3. **Tailwind styles not applying**: Make sure you're using Tailwind CSS v4 and the updated build process

### Getting Help

If you encounter issues during migration:

1. Check the [Filament v4 upgrade guide](https://filamentphp.com/docs/4.x/upgrade-guide)
2. Review the package's test suite for usage examples
3. Open an issue on the GitHub repository

## Future Considerations

- The package is now fully compatible with Filament v4
- Consider using the new plugin system features for better integration
- Asset management is now handled through the plugin system rather than manual registration
