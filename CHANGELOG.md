# Changelog

All notable changes to `filament-table-layout-toggle` will be documented in this file.

## 3.0.0 - 2024-12-19

### 🚀 Major Release: Filament v4 Support

- **BREAKING**: Upgraded to Filament v4 compatibility
- **BREAKING**: PHP 8.2+ required (was 8.1+)
- **BREAKING**: Laravel 11.28+ required (Laravel 10.x no longer supported)
- **BREAKING**: Tailwind CSS v4.0+ required for custom themes
- **BREAKING**: Removed deprecated `FilamentView::registerRenderHook()` usage
- **BREAKING**: Asset registration moved to plugin system

### ✨ New Features
- Full compatibility with Filament v4
- Enhanced plugin system integration
- Improved asset management through v4 plugin system

### 🔧 Technical Improvements
- Updated all dependencies to latest compatible versions
- Modernized build process for Tailwind CSS v4
- Enhanced service provider with v4 patterns
- Improved error handling and safety checks

### 📚 Documentation
- Comprehensive migration guide from v3 to v4
- Updated requirements and compatibility matrix
- Automated upgrade script for easy migration
- Detailed breaking changes documentation

## 2.0.0 - 2024-08-06

- Added support for cache storage by @Ercogx & @tgeorgel
- Configuration key has changed from `persist.enabled` to `persist.persister`

## 1.2.1 - 2024-07-23

- Return type typo fix by @lairg99

## 1.2.0 - 2024-03-18

- Laravel 11 support

## 1.1.0 - 2024-02-12

- Added support for filament table standalone package

## 1.0.0 - 2024-01-02

- Initial stable release
