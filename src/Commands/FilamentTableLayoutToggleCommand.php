<?php

namespace Hydrat\TableLayoutToggle\Commands;

use Illuminate\Console\Command;

class TableLayoutToggleCommand extends Command
{
    public $signature = 'filament-table-layout-toggle';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
