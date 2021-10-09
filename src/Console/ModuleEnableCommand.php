<?php

namespace Arikod\Module\Console;

class ModuleEnableCommand extends AbstractModuleManageCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:enable
                    { module?* : Name of the module }
                    {--all : Enable all modules}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Enables specified modules';

    /**
     * Enable modules
     *
     * @return bool
     */
    protected function isEnable(): bool
    {
        return true;
    }
}
