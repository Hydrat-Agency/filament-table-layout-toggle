<?php

namespace Hydrat\TableLayoutToggle\Persisters;

use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Facades\FilamentView;
use Filament\Widgets\TableWidget;
use Hydrat\TableLayoutToggle\Contracts\LayoutPersister;
use Hydrat\TableLayoutToggle\Support\Config;
use Illuminate\Contracts\View\View;

class LocalStoragePersister extends AbstractPersister implements LayoutPersister
{
    /**
     * Create a default key for the state.
     */
    public function defaultKey(): string
    {
        $stateSharedBetweenComponents = Config::shouldShareLayoutBetweenPages();

        return $stateSharedBetweenComponents
            ? 'tableLayoutView'
            : 'tableLayoutView::'.md5(url()->current());
    }

    public function setState(string $layoutState): self
    {
        /**
         * The state is managed in the frontend.
         */

        return $this;
    }

    public function getState(): ?string
    {
        /**
         * The state is managed in the frontend.
         */

        return null;
    }

    /**
     * Called when the HasToggledTableLayout trait has booted.
     */
    public function onComponentBooted(): void
    {
        FilamentView::registerRenderHook(
            $this->getTableHookNameFromFilamentClassType(),
            fn (): View => $this->renderLayoutViewPersister(),
            scopes: get_class($this->component),
        );
    }

    /**
     * Render the local storage persister view.
     */
    protected function renderLayoutViewPersister(): View
    {
        return view('table-layout-toggle::layout-view-persister', [
            'persistEnabled' => true,
            'persistKey' => $this->getKey(),
        ]);
    }

    /**
     * Get the table hook name from the Filament class type.
     */
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
        return is_subclass_of($this->component, ListRecords::class);
    }

    protected function isRelationManagerFilamentClass(): bool
    {
        return is_subclass_of($this->component, RelationManager::class);
    }

    protected function isTableWidgetFilamentClass(): bool
    {
        return is_subclass_of($this->component, TableWidget::class);
    }

    protected function isManageRelatedRecordsFilamentClass(): bool
    {
        return is_subclass_of($this->component, ManageRelatedRecords::class);
    }
}
