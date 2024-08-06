<?php

namespace Hydrat\TableLayoutToggle\Persisters;

use Hydrat\TableLayoutToggle\Contracts\LayoutPersister;
use Hydrat\TableLayoutToggle\Support\Config;
use Illuminate\Support\Arr;
use Livewire\Component;

abstract class AbstractPersister implements LayoutPersister
{
    protected string $cacheKey;

    public function __construct(
        protected Component $component,
    ) {
        $this->setKey($this->defaultKey());
    }

    /**
     * Create a default key for the state.
     */
    public function defaultKey(): string
    {
        $stateSharedBetweenComponents = Config::shouldShareLayoutBetweenPages();

        $cacheUniqueKeyParts = match ($stateSharedBetweenComponents) {
            true => [auth()->id()],
            false => [auth()->id(), get_class($this->component)],
        };

        return 'tableLayoutView::'.md5(Arr::join($cacheUniqueKeyParts, '::'));
    }

    /**
     * Set the cache key for the state.
     */
    public function setKey(string $key): self
    {
        $this->cacheKey = $key;

        return $this;
    }

    /**
     * Get the cache key for the state.
     */
    public function getKey(): string
    {
        return $this->cacheKey;
    }

    /**
     * Called when the HasToggledTableLayout trait boots.
     */
    public function onComponentBoot(): void
    {
        //
    }

    /**
     * Called when the HasToggledTableLayout trait has booted.
     */
    public function onComponentBooted(): void
    {
        //
    }
}
