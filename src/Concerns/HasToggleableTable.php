<?php

namespace Hydrat\TableLayoutToggle\Concerns;

use Filament\Support\Facades\FilamentView;
use Hydrat\TableLayoutToggle\Contracts\LayoutPersister;
use Hydrat\TableLayoutToggle\Support\Config;
use Illuminate\Contracts\View\View;

trait HasToggleableTable
{
    public ?string $layoutView = null;

    protected LayoutPersister $layoutPersister;

    public function initializeHasToggleableTable()
    {
        $this->listeners = $this->listeners + [
            'changeLayoutView' => 'changeLayoutView',
            'layoutViewChanged' => '$refresh',
        ];
    }

    public function updatedLayoutView($value)
    {
        $this->dispatch('layoutViewChanged', $value);
    }

    public function bootHasToggleableTable()
    {
        $persisterClass = Config::shouldPersistLayoutUsing();

        $this->layoutPersister = new $persisterClass($this);

        $this->configurePersister();

        $this->layoutPersister->onComponentBoot();

        $this->layoutView = $this->layoutPersister->getState() ?: $this->layoutView;
    }

    /**
     * Modify the persister configuration,
     * or initialize a new one for this component.
     */
    public function configurePersister(): void
    {
        // $this->layoutPersister
        //     ->setKey(...)
        //     ->setCacheDriver(...)
        //     ->setExpiration(...);
    }

    public function bootedHasToggleableTable()
    {
        $this->layoutPersister->onComponentBooted();

        if (Config::toggleActionEnabled() && ($filamentHook = Config::toggleActionPosition())) {
            $this->registerLayoutViewToogleActionHook($filamentHook);
        }
    }

    public function getDefaultLayoutView(): string
    {
        return Config::defaultLayout();
    }

    public function isGridLayout(): bool
    {
        return $this->getLayoutView() === 'grid';
    }

    public function isListLayout(): bool
    {
        return $this->getLayoutView() === 'list';
    }

    public function getLayoutView(): string
    {
        return $this->layoutView ?? $this->getDefaultLayoutView();
    }

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

    public function changeLayoutView(): void
    {
        $this->layoutView = $this->isListLayout() ? 'grid' : 'list';

        $this->layoutPersister->setState($this->layoutView);

        $this->dispatch('layoutViewChanged', $this->layoutView);
    }
}
