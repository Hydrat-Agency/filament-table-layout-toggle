<?php

namespace Hydrat\TableLayoutToggle;

use Filament\Panel;
use Filament\Contracts\Plugin;
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
        //
    }

    public function boot(Panel $panel): void
    {
        //
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
