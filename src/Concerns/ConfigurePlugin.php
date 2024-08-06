<?php

namespace Hydrat\TableLayoutToggle\Concerns;

use Closure;
use Filament\Support\Concerns\EvaluatesClosures;
use Hydrat\TableLayoutToggle\Contracts\LayoutPersister as LayoutPersisterContract;
use Hydrat\TableLayoutToggle\Persisters;

trait ConfigurePlugin
{
    use EvaluatesClosures;

    protected string|Closure $defaultLayout = 'list';

    /** @deprecated */
    protected bool|Closure $persistLayoutInLocalStorage = true;

    /** @deprecated */
    protected bool|Closure $persistLayoutInCache = true;

    protected string|Closure $layoutPersister = Persisters\LocalStoragePersister::class;

    protected ?string $cacheStore = null;

    protected ?int $cacheTtl = null;

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

    public function persistLayoutUsing(
        string|Closure $persister = Persisters\LocalStoragePersister::class,
        ?string $cacheStore = null,
        ?int $cacheTtl = null,
    ): static {
        if (is_string($persister)) {
            if (! class_exists($persister)) {
                throw new \InvalidArgumentException("The persister class [{$persister}] does not exist.");
            }

            if (! in_array(LayoutPersisterContract::class, class_implements($persister))) {
                throw new \InvalidArgumentException("The persister class [{$persister}] must implement the [".LayoutPersisterContract::class.'] interface.');
            }
        }

        $this->layoutPersister = $persister;
        $this->cacheStore = $cacheStore;
        $this->cacheTtl = $cacheTtl;

        return $this;
    }

    /**
     * @deprecated v2.0.0 Use persistLayoutUsing() instead.
     */
    public function persistLayoutInLocalStorage(bool|Closure $condition = true): static
    {
        if (is_bool($condition) && $condition) {
            $this->persistLayoutUsing(Persisters\LocalStoragePersister::class);
        }

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

    public function shouldPersistLayoutUsing(): string
    {
        return $this->evaluate($this->layoutPersister);
    }

    public function shouldUseCacheStore(): ?string
    {
        return $this->cacheStore;
    }

    public function shouldUseCacheTtl(): ?int
    {
        return $this->cacheTtl;
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
