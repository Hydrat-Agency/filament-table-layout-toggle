<?php

namespace Hydrat\TableLayoutToggle\Persisters;

use Hydrat\TableLayoutToggle\Contracts\LayoutPersister;
use Hydrat\TableLayoutToggle\Support\Config;
use Livewire\Component;

class CachePersister extends AbstractPersister implements LayoutPersister
{
    protected ?string $cacheStore;

    protected int $ttl;

    public function __construct(
        protected Component $component,
    ) {
        parent::__construct($component);

        $this->cacheStore = Config::shouldUseCacheStore();
        $this->ttl = Config::shouldUseCacheTtl() ?: 60 * 24 * 7;
    }

    public function setCacheStore(string $driver): self
    {
        $this->cacheStore = $driver;

        return $this;
    }

    public function getCacheStore(): ?string
    {
        return $this->cacheStore;
    }

    public function setExpiration(int $expirationMinutes): self
    {
        $this->ttl = $expirationMinutes;

        return $this;
    }

    public function getExpiration(): int
    {
        return $this->ttl;
    }

    public function getState(): ?string
    {
        $store = $this->getCacheStore();
        $key = $this->getKey();

        return cache()->store($store)->get($key);
    }

    public function setState(string $layoutState): self
    {
        $store = $this->getCacheStore();
        $key = $this->getKey();
        $ttl = $this->getExpiration();

        cache()->store($store)->put(
            $key,
            $layoutState,
            now()->addMinutes($ttl),
        );

        return $this;
    }
}
