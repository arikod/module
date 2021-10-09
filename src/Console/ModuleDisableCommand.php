<?php

namespace Arikod\Module\Console;

class ModuleDisableCommand extends AbstractModuleManageCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:disable
                    { module?* : Name of the module }
                    {--all : Disable all modules}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Disables specified modules';

    /**
     * Disable modules
     *
     * @return bool
     */
    protected function isEnable(): bool
    {
        return false;
    }
}
