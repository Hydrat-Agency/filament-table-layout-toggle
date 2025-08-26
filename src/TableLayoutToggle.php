<?php

namespace Hydrat\TableLayoutToggle;

use Filament\Actions\Action;
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

    /**
     * @deprecated v3.0.0 Use getToggleViewAction() instead, as Filament now has a single Action class.
     */
    public function getToggleViewTableAction(bool $compact = false): Action
    {
        return $this->getToggleViewAction($compact);
    }
}
