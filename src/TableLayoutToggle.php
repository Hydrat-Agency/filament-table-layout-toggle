<?php

namespace Hydrat\TableLayoutToggle;

use Filament\Actions\Action;
use Filament\Tables\Actions\Action as TableAction;
use Hydrat\TableLayoutToggle\Support\Config;

class TableLayoutToggle
{
    public function getToggleViewAction(bool $compact = false): Action
    {
        return Action::make('toggle-table-view-gt')
            ->color('gray')
            ->hiddenLabel(true)
            ->icon(function ($livewire): string {
                return $livewire->layoutView === 'grid'
                    ? Config::getListLayoutButtonIcon()
                    : Config::getGridLayoutButtonIcon();
            })
            ->action(function ($livewire): void {
                $livewire->dispatch('changeLayoutView');
            })
            ->extraAttributes([
                'class' => $compact ? '!p-0 !m-0 border-none' : '',
            ]);
    }

    public function getToggleViewTableAction(bool $compact = false): TableAction
    {
        return TableAction::make('toggle-table-view-gt')
            ->color('gray')
            ->hiddenLabel(true)
            ->icon(function ($livewire): string {
                return $livewire->layoutView === 'grid'
                    ? Config::getListLayoutButtonIcon()
                    : Config::getGridLayoutButtonIcon();
            })
            ->action(function ($livewire): void {
                $livewire->dispatch('changeLayoutView');
            })
            ->extraAttributes([
                'class' => $compact ? '!p-0 !m-0 border-none' : '',
            ]);
    }
}
