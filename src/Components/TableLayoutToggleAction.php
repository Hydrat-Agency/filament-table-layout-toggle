<?php

namespace Hydrat\TableLayoutToggle\Components;

use Livewire\Component;
use Filament\Actions\Action;
use Illuminate\Contracts\View\View;
use Filament\Forms\Contracts\HasForms;
use Filament\Actions\Contracts\HasActions;
use Hydrat\TableLayoutToggle\Support\Config;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Actions\Concerns\InteractsWithActions;

class TableLayoutToggleAction extends Component implements HasActions, HasForms
{
    use InteractsWithActions;
    use InteractsWithForms;

    public function toggleAction(): Action
    {
        return Action::make('toggle-table-view')
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
                'class' => '!p-0 !m-0 border-none',
            ]);
    }

    public function render(): View
    {
        return view('table-layout-toggle::toggle-action');
    }
}
