<?php

namespace Hydrat\TableLayoutToggle\Concerns;

use Filament\Support\Facades\FilamentView;
use Hydrat\TableLayoutToggle\Contracts\LayoutPersister;
use Hydrat\TableLayoutToggle\Support\Config;
use Illuminate\Contracts\View\View;

trait HasToggleableTable
{
    public ?string $layoutView = null;

    /**
     * Tracks the layout view that the table was last built for,
     * so renderingHasToggleableTable() can decide when a rebuild is needed.
     */
    protected ?string $tableBuiltForLayout = null;

    protected LayoutPersister $layoutPersister;

    public function initializeHasToggleableTable()
    {
        $this->listeners = $this->listeners + [
            'changeLayoutView' => 'changeLayoutView',
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

    public function bootedHasToggleableTable()
    {
        // Record which layout the table was just built for (bootedInteractsWithTable
        // runs in the same booted phase and builds the table before this method).
        $this->tableBuiltForLayout = $this->getLayoutView();

        $this->layoutPersister->onComponentBooted();

        if (Config::toggleActionEnabled() && ($filamentHook = Config::toggleActionPosition())) {
            $this->registerLayoutViewToogleActionHook($filamentHook);
        }
    }

    /**
     * Runs just before the Livewire view renders, after all methods and property
     * updates have been processed. Rebuilds the table if the layout changed since
     * bootedInteractsWithTable() built it — without relying on updated* hooks
     * (which Livewire only fires for payload-driven property changes, not PHP-side
     * assignments inside methods like changeLayoutView()).
     */
    public function renderingHasToggleableTable(): void
    {
        if ($this->tableBuiltForLayout !== $this->getLayoutView()) {
            $this->table = $this->table($this->makeTable());
            $this->tableBuiltForLayout = $this->getLayoutView();
        }

        if (! $this->getTable()->hasColumnsLayout() && blank($this->tableColumns)) {
            $this->cachedDefaultTableColumnState = null;
            $this->initTableColumnManager();
        }
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

    public function getDefaultLayoutView(): string
    {
        if (Config::autoMobileLayout() && $this->isMobileDevice()) {
            return 'grid';
        }

        return Config::defaultLayout();
    }

    protected function isMobileDevice(): bool
    {
        return (bool) preg_match(
            '/Mobile|Android|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i',
            request()->header('User-Agent', '')
        );
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
    }
}
