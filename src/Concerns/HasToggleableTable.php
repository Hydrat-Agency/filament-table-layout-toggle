<?php

namespace Hydrat\TableLayoutToggle\Concerns;

use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Facades\FilamentView;
use Filament\Tables\Actions\Action as TableAction;
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
            'panels::resource.pages.list-records.table.after',
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

    protected function getHookName(): string
    {
        if ($this->isRelationManager()) {
            return 'panels::resource.relation-manager.after';
        }

        if ($this->isTableWidget()) {
            return 'widgets::table-widget.end';
        }

        if ($this->isManageRelatedRecords()) {
            return 'panels::resource.pages.manage-related-records.table.after';
        }

        return 'panels::resource.pages.list-records.table.after';
    }

    protected function isResource(): bool
    {
        return is_subclass_of($this, ListRecords::class);
    }

    protected function isRelationManager(): bool
    {
        return is_subclass_of($this, RelationManager::class);
    }

    protected function isTableWidget(): bool
    {
        return is_subclass_of($this, TableWidget::class);
    }

    protected function isManageRelatedRecords(): bool
    {
        return is_subclass_of($this, ManageRelatedRecords::class);
    }

    public function getToggleTableAction(bool $compact = true): TableAction
    {
        return TableAction::make('toggle-table-view-gt')
            ->color('gray')
            ->hiddenLabel(true)
            ->icon(function ($livewire): string {
                return $livewire->layoutView === 'grid'
                    ? TableLayoutTogglePlugin::get()->getListLayoutButtonIcon()
                    : TableLayoutTogglePlugin::get()->getGridLayoutButtonIcon();
            })
            ->action(function ($livewire): void {
                $livewire->dispatch('changeLayoutView');
            })
            ->extraAttributes([
                'class' => $compact ? '!p-0 !m-0 border-none' : '',
            ]);
    }
}
