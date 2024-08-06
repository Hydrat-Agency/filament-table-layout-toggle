<?php

namespace Hydrat\TableLayoutToggle\Contracts;

use Livewire\Component;

interface LayoutPersister
{
    public function __construct(Component $component);

    /**
     * Get the cache key for the state.
     */
    public function getKey(): string;

    /**
     * Set the cache key for the state.
     */
    public function setKey(string $key): self;

    /**
     * Create a default key for the state.
     */
    public function defaultKey(): string;

    /**
     * Get the layout state from the persister
     */
    public function getState(): ?string;

    /**
     * Set the layout state from the persister
     */
    public function setState(string $layoutState): self;

    /**
     * Called when the HasToggledTableLayout trait boots.
     */
    public function onComponentBoot(): void;

    /**
     * Called when the HasToggledTableLayout trait has booted.
     */
    public function onComponentBooted(): void;
}
