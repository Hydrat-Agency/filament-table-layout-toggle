<?php

namespace Hydrat\TableLayoutToggle\Resources;

use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords as FilamentListRecords;
use Hydrat\TableLayoutToggle\Concerns\HasToggleableTable;
use Hydrat\TableLayoutToggle\Support\Config;

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
                        ? Config::getListLayoutButtonIcon()
                        : Config::getGridLayoutButtonIcon();
                })
                ->action(function ($livewire): void {
                    $livewire->dispatch('changeLayoutView');
                }),
        ];
    }
}
