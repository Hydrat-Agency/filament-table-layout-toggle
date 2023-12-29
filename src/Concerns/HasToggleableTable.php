<?php

namespace Hydrat\TableLayoutToggle\Concerns;

use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Facades\FilamentView;
use Filament\Widgets\TableWidget;
use Hydrat\TableLayoutToggle\TableLayoutTogglePlugin;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Blade;

trait HasToggleableTable
{
    public string $layoutView = 'grid';

    public function initializeHasToggleableTable()
    {
        return $this->listeners = $this->listeners + [
            'changeLayoutView' => 'changeLayoutView',
            'layoutViewChanged' => '$refresh',
        ];
    }

    public function bootedHasToggleableTable()
    {
        if (TableLayoutTogglePlugin::get()->shouldPersistLayoutInLocalStorage()) {
            $this->registerLayoutViewPersisterHook();
        }

        if (($filamentHook = TableLayoutTogglePlugin::get()->toggleActionPosition()) !== false) {
            $this->registerLayoutViewToogleActionHook($filamentHook);
        }
    }

    protected function registerLayoutViewPersisterHook()
    {
        $sharedPersistedName = TableLayoutTogglePlugin::get()->shouldShareLayoutBetweenPages();

        $saveAsName = $sharedPersistedName
            ? 'tableLayoutView'
            : 'tableLayoutView::'.md5(url()->current());

        FilamentView::registerRenderHook(
            $this->getTableHookNameFromFilamentClassType(),
            fn (): View => view('table-layout-toggle::layout-view-persister', compact('saveAsName')),
            scopes: HasToggleableTable::class,
        );
    }

    protected function registerLayoutViewToogleActionHook(string $filamentHook)
    {
        FilamentView::registerRenderHook(
            $filamentHook,
            fn (): string => Blade::render('@livewire(\'\')'),
            scopes: HasToggleableTable::class,
        );
    }

    public function changeLayoutView(): void
    {
        $this->layoutView = $this->layoutView === 'list' ? 'grid' : 'list';
        $this->dispatch('layoutViewChanged', $this->layoutView);
    }

    public function isGridLayout(): bool
    {
        return $this->layoutView === 'grid';
    }

    public function isListLayout(): bool
    {
        return $this->layoutView === 'list';
    }

    public function getLayoutView(): string
    {
        return $this->layoutView;
    }

    protected function getTableHookNameFromFilamentClassType(): string
    {
        return match (true) {
            $this->isResourceFilamentClass() => 'panels::resource.pages.list-records.table.before',
            $this->isRelationManagerFilamentClass() => 'panels::resource.relation-manager.before',
            $this->isTableWidgetFilamentClass() => 'widgets::table-widget.start',
            $this->isManageRelatedRecordsFilamentClass() => 'panels::resource.pages.manage-related-records.table.before',
            default => 'panels::resource.pages.list-records.table.before',
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
