<?php

namespace Hydrat\TableLayoutToggle;

use Filament\Support\Assets\AlpineComponent;
use Filament\Support\Assets\Asset;
use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Facades\FilamentIcon;
use Hydrat\TableLayoutToggle\Commands\TableLayoutToggleCommand;
use Hydrat\TableLayoutToggle\Testing\TestsTableLayoutToggle;
use Illuminate\Filesystem\Filesystem;
use Livewire\Features\SupportTesting\Testable;
use Livewire\Livewire;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class TableLayoutToggleServiceProvider extends PackageServiceProvider
{
    public static string $name = 'table-layout-toggle';

    public static string $viewNamespace = 'table-layout-toggle';

    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package->name(static::$name)
            ->hasCommands($this->getCommands())
            ->hasInstallCommand(function (InstallCommand $command) {
                $command
                    ->publishConfigFile()
                    // ->publishMigrations()
                    // ->askToRunMigrations()
                    ->askToStarRepoOnGitHub('hydrat/filament-table-layout-toggle');
            });

        $configFileName = $package->shortName();

        if (file_exists($package->basePath("/../config/{$configFileName}.php"))) {
            $package->hasConfigFile();
        }

        if (file_exists($package->basePath('/../database/migrations'))) {
            $package->hasMigrations($this->getMigrations());
        }

        if (file_exists($package->basePath('/../resources/lang'))) {
            $package->hasTranslations();
        }

        if (file_exists($package->basePath('/../resources/views'))) {
            $package->hasViews(static::$viewNamespace);
        }
    }

    public function packageRegistered(): void {}

    public function packageBooted(): void
    {
        // Asset Registration
        FilamentAsset::register(
            $this->getAssets(),
            $this->getAssetPackageName()
        );

        FilamentAsset::registerScriptData(
            $this->getScriptData(),
            $this->getAssetPackageName()
        );

        // Icon Registration
        FilamentIcon::register($this->getIcons());

        // Handle Stubs
        if (app()->runningInConsole()) {
            foreach (app(Filesystem::class)->files(__DIR__.'/../stubs/') as $file) {
                $this->publishes([
                    $file->getRealPath() => base_path("stubs/filament-table-layout-toggle/{$file->getFilename()}"),
                ], 'filament-table-layout-toggle-stubs');
            }
        }

        Livewire::component('table-layout-toggle-action', Components\TableLayoutToggleAction::class);

        // Testing
        // Testable::mixin(new TestsTableLayoutToggle());
    }

    protected function getAssetPackageName(): ?string
    {
        return 'hydrat/filament-table-layout-toggle';
    }

    /**
     * @return array<Asset>
     */
    protected function getAssets(): array
    {
        return [
            // AlpineComponent::make('filament-table-layout-toggle', __DIR__ . '/../resources/dist/components/filament-table-layout-toggle.js'),
            // Css::make('filament-table-layout-toggle-styles', __DIR__.'/../resources/dist/filament-table-layout-toggle.css'),
            // Js::make('filament-table-layout-toggle-scripts', __DIR__.'/../resources/dist/filament-table-layout-toggle.js'),
        ];
    }

    /**
     * @return array<class-string>
     */
    protected function getCommands(): array
    {
        return [
            // TableLayoutToggleCommand::class,
        ];
    }

    /**
     * @return array<string>
     */
    protected function getIcons(): array
    {
        return [];
    }

    /**
     * @return array<string>
     */
    protected function getRoutes(): array
    {
        return [];
    }

    /**
     * @return array<string, mixed>
     */
    protected function getScriptData(): array
    {
        return [];
    }

    /**
     * @return array<string>
     */
    protected function getMigrations(): array
    {
        return [
            // 'create_filament-table-layout-toggle_table',
        ];
    }
}
