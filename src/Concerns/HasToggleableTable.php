<?php

namespace Hydrat\TableLayoutToggle\Concerns;

use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Facades\FilamentView;
use Filament\Widgets\TableWidget;
use Hydrat\TableLayoutToggle\Support\Config;
use Illuminate\Contracts\View\View;

trait HasToggleableTable
{
    public $layoutView;

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

    public function bootedHasToggleableTable()
    {
        if ($this->persistToggleEnabled()) {
            $this->registerLayoutViewPersisterHook();
        }

        if (Config::toggleActionEnabled() && ($filamentHook = Config::toggleActionPosition())) {
            $this->registerLayoutViewToogleActionHook($filamentHook);
        }
    }

    protected function persistToggleEnabled(): bool
    {
        return Config::shouldPersistLayoutInLocalStorage();
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

    protected function persistToggleStatusName(): string
    {
        $sharedPersistedName = Config::shouldShareLayoutBetweenPages();

        return $sharedPersistedName
            ? 'tableLayoutView'
            : 'tableLayoutView::'.md5(url()->current());
    }

    protected function registerLayoutViewPersisterHook()
    {
        FilamentView::registerRenderHook(
            $this->getTableHookNameFromFilamentClassType(),
            fn (): View => $this->renderLayoutViewPersister(),
            scopes: static::class,
        );
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

    protected function renderLayoutViewPersister(): View
    {
        return view('table-layout-toggle::layout-view-persister', [
            'persistIsEnabled' => $this->persistToggleEnabled(),
            'persistToggleStatusName' => $this->persistToggleStatusName(),
        ]);
    }

    public function changeLayoutView(): void
    {
        $this->layoutView = $this->isListLayout() ? 'grid' : 'list';

        $this->dispatch('layoutViewChanged', $this->layoutView);
    }

    protected function getTableHookNameFromFilamentClassType(): string
    {
        return match (true) {
            $this->isResourceFilamentClass() => 'panels::resource.pages.list-records.table.after',
            $this->isRelationManagerFilamentClass() => 'panels::resource.relation-manager.after',
            $this->isTableWidgetFilamentClass() => 'widgets::table-widget.start',
            $this->isManageRelatedRecordsFilamentClass() => 'panels::resource.pages.manage-related-records.table.after',
            default => 'panels::resource.pages.list-records.table.after',
        };
    }

    protected function isResourceFilamentClass(): bool
    {
        return is_subclass_of($this, ListRecords::class);
    }

    protected function isRelationManagerFilamentClass(): bool
    {
        return is_subclass_of($this, RelationManager::class);
    }

    protected function isTableWidgetFilamentClass(): bool
    {
        return is_subclass_of($this, TableWidget::class);
    }

    protected function isManageRelatedRecordsFilamentClass(): bool
    {
        return is_subclass_of($this, ManageRelatedRecords::class);
    }
}
