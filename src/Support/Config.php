<?php

namespace Hydrat\TableLayoutToggle\Support;

use Hydrat\TableLayoutToggle\TableLayoutTogglePlugin;

class Config
{
    public static function pluginRegistered(): bool
    {
        return filament()->getCurrentPanel() && filament()->hasPlugin('filament-table-layout-toggle');
    }

    public static function defaultLayout(): bool
    {
        if (self::pluginRegistered()) {
            return TableLayoutTogglePlugin::get()->defaultLayout();
        }

        return config('table-layout-toggle.default_layout', 'list');
    }

    public static function shouldPersistLayoutInLocalStorage(): bool
    {
        if (self::pluginRegistered()) {
            return TableLayoutTogglePlugin::get()->shouldPersistLayoutInLocalStorage();
        }

        return config('table-layout-toggle.local_storage', true);
    }

    public static function shouldShareLayoutBetweenPages(): bool
    {
        if (self::pluginRegistered()) {
            return TableLayoutTogglePlugin::get()->shouldShareLayoutBetweenPages();
        }

        return config('table-layout-toggle.share_between_pages', false);
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
