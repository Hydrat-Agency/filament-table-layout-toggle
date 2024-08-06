<?php

namespace Hydrat\TableLayoutToggle\Support;

use Hydrat\TableLayoutToggle\Contracts\LayoutPersister;
use Hydrat\TableLayoutToggle\Persisters;
use Hydrat\TableLayoutToggle\TableLayoutTogglePlugin;

class Config
{
    public static function pluginRegistered(): bool
    {
        return filament()->getCurrentPanel() && filament()->hasPlugin('filament-table-layout-toggle');
    }

    public static function defaultLayout(): string
    {
        if (self::pluginRegistered()) {
            return TableLayoutTogglePlugin::get()->defaultLayout();
        }

        return config('table-layout-toggle.default_layout', 'list');
    }

    /**
     * @deprecated v2.0.0 Use shouldPersistLayoutUsing() instead.
     */
    public static function shouldPersistLayoutInLocalStorage(): bool
    {
        return static::shouldPersistLayoutUsing() === Persisters\LocalStoragePersister::class;
    }

    /**
     * @return string<LayoutPersister>
     */
    public static function shouldPersistLayoutUsing(): string
    {
        if (self::pluginRegistered()) {
            return TableLayoutTogglePlugin::get()->shouldPersistLayoutUsing();
        }

        return config('table-layout-toggle.persist.persiter', Persisters\LocalStoragePersister::class);
    }

    public static function shouldUseCacheStore(): ?string
    {
        if (self::pluginRegistered()) {
            return TableLayoutTogglePlugin::get()->shouldUseCacheStore();
        }

        return config('table-layout-toggle.persist.cache.store', null);
    }

    public static function shouldUseCacheTtl(): ?int
    {
        if (self::pluginRegistered()) {
            return TableLayoutTogglePlugin::get()->shouldUseCacheTtl();
        }

        return config('table-layout-toggle.persist.cache.time', 60 * 24 * 7);
    }

    public static function shouldShareLayoutBetweenPages(): bool
    {
        if (self::pluginRegistered()) {
            return TableLayoutTogglePlugin::get()->shouldShareLayoutBetweenPages();
        }

        return config('table-layout-toggle.persist.share_between_pages', false);
    }

    public static function toggleActionEnabled(): bool
    {
        if (self::pluginRegistered()) {
            return TableLayoutTogglePlugin::get()->toggleActionEnabled();
        }

        return config('table-layout-toggle.toggle_action.enabled', true);
    }

    public static function toggleActionPosition(): string
    {
        if (self::pluginRegistered()) {
            return TableLayoutTogglePlugin::get()->toggleActionPosition();
        }

        return config('table-layout-toggle.toggle_action.position', 'tables::toolbar.search.after');
    }

    public static function getListLayoutButtonIcon(): string
    {
        if (self::pluginRegistered()) {
            return TableLayoutTogglePlugin::get()->getListLayoutButtonIcon();
        }

        return config('table-layout-toggle.toggle_action.list_icon', 'heroicon-o-list-bullet');
    }

    public static function getGridLayoutButtonIcon(): string
    {
        if (self::pluginRegistered()) {
            return TableLayoutTogglePlugin::get()->getGridLayoutButtonIcon();
        }

        return config('table-layout-toggle.toggle_action.grid_icon', 'heroicon-o-squares-2x2');
    }
}
