<?php

namespace Hydrat\TableLayoutToggle\Persisters;

use Hydrat\TableLayoutToggle\Contracts\LayoutPersister;

class DisabledPersister extends AbstractPersister implements LayoutPersister
{
    public function setState(string $layoutState): self
    {
        return $this;
    }

    public function getState(): ?string
    {
        return null;
    }
}
