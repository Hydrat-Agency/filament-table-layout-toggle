<?php

namespace Hydrat\TableLayoutToggle\Concerns;

use Closure;
use Filament\Support\Concerns\EvaluatesClosures;

trait ConfigurePlugin
{
    use EvaluatesClosures;

    protected bool|Closure $persistLayoutInLocalStorage = true;

    protected bool|Closure $shareLayoutBetweenPages = false;

    protected string|Closure $listLayoutButtonIcon = 'heroicon-o-list-bullet';

    protected string|Closure $gridLayoutButtonIcon = 'heroicon-o-list-bullet';

    protected false|string|Closure $toggleActionPosition = 'tables::toolbar.search.after';

    public function persistLayoutInLocalStorage(bool|Closure $condition = true): static
    {
        $this->persistLayoutInLocalStorage = $condition;

        return $this;
    }

    public function shareLayoutBetweenPages(bool|Closure $condition = true): static
    {
        $this->shareLayoutBetweenPages = $condition;

        return $this;
    }

    public function listLayoutButtonIcon(string|Closure $icon = 'heroicon-o-list-bullet'): static
    {
        $this->listLayoutButtonIcon = $icon;

        return $this;
    }

    public function gridLayoutButtonIcon(string|Closure $icon = 'heroicon-o-squares-2x2'): static
    {
        $this->gridLayoutButtonIcon = $icon;

        return $this;
    }

    public function displayToggleAction(false|string|Closure $filamentHook = 'tables::toolbar.search.after'): static
    {
        $this->toggleActionPosition = $filamentHook;

        return $this;
    }

    public function shouldPersistLayoutInLocalStorage(): bool
    {
        return $this->evaluate($this->persistLayoutInLocalStorage);
    }

    public function shouldShareLayoutBetweenPages(): bool
    {
        return $this->evaluate($this->shareLayoutBetweenPages);
    }

    public function getListLayoutButtonIcon(): string
    {
        return $this->evaluate($this->listLayoutButtonIcon);
    }

    public function getGridLayoutButtonIcon(): string
    {
        return $this->evaluate($this->gridLayoutButtonIcon);
    }

    public function toggleActionPosition(): false|string
    {
        return $this->evaluate($this->toggleActionPosition);
    }
}
