<?php

namespace Hydrat\TableLayoutToggle;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Hydrat\TableLayoutToggle\Concerns\ConfigurePlugin;

class TableLayoutTogglePlugin implements Plugin
{
    use ConfigurePlugin;

    public function getId(): string
    {
        return 'filament-table-layout-toggle';
    }

    public function register(Panel $panel): void
    {
        // Register any panel-specific configurations here
    }

    public function boot(Panel $panel): void
    {
        // Boot any panel-specific functionality here
    }

    public static function make(): static
    {
        return app(static::class);
    }

    public static function get(): static
    {
        /** @var static $plugin */
        $plugin = filament(app(static::class)->getId());

        return $plugin;
    }
}
