<?php

namespace Hydrat\TableLayoutToggle\Concerns;

use Closure;
use Filament\Support\Concerns\EvaluatesClosures;

trait ConfigurePlugin
{
    use EvaluatesClosures;

    protected string|Closure $defaultLayout = 'list';

    protected bool|Closure $persistLayoutInLocalStorage = true;

    protected bool|Closure $shareLayoutBetweenPages = false;

    protected string|Closure $listLayoutButtonIcon = 'heroicon-o-list-bullet';

    protected string|Closure $gridLayoutButtonIcon = 'heroicon-o-squares-2x2';

    protected bool|Closure $toggleActionEnabled = true;

    protected string|Closure $toggleActionPosition = 'tables::toolbar.search.after';

    public function setDefaultLayout(string|Closure $defaultLayout = 'list'): static
    {
        $this->defaultLayout = $defaultLayout;

        return $this;
    }

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

    public function displayToggleAction(bool|Closure $enabled = true): static
    {
        $this->toggleActionEnabled = $enabled;

        return $this;
    }

    public function toggleActionHook(string|Closure $filamentHook = 'tables::toolbar.search.after'): static
    {
        $this->toggleActionPosition = $filamentHook;

        return $this;
    }

    public function defaultLayout(): string
    {
        return $this->evaluate($this->defaultLayout);
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

    public function toggleActionEnabled(): bool
    {
        return $this->evaluate($this->toggleActionEnabled);
    }

    public function toggleActionPosition(): string
    {
        return $this->evaluate($this->toggleActionPosition);
    }
}
