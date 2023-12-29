<?php

namespace Hydrat\TableLayoutToggle\Resources;

use Filament\Actions\Action;
use Hydrat\TableLayoutToggle\TableLayoutTogglePlugin;
use Hydrat\TableLayoutToggle\Concerns\HasToggleableTable;
use Filament\Resources\Pages\ListRecords as FilamentListRecords;

class ListRecords extends FilamentListRecords
{
    use HasToggleableTable;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('toggle-table-view')
                ->color('gray')
                ->hiddenLabel(true)
                ->icon(function ($livewire): string {
                    return $livewire->layoutView === 'grid'
                        ? TableLayoutTogglePlugin::get()->getListLayoutButtonIcon()
                        : TableLayoutTogglePlugin::get()->getGridLayoutButtonIcon();
                })
                ->action(function ($livewire): void {
                    $livewire->dispatch('changeLayoutView');
                }),
        ];
    }
}
