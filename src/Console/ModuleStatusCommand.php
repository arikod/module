<?php

namespace Arikod\Module\Console;

use Arikod\Module\ModuleList;
use Arikod\Module\FullModuleList;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;

class ModuleStatusCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:status
                    { module-names?* : The preset type (bootstrap) }
                    {--enabled : Print only enabled modules}
                    {--disabled : Print only disabled modules}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Displays status of modules';

    public function handle()
    {
        $moduleNames = $this->argument('module-names');

        if (!empty($moduleNames)) {
            foreach ($moduleNames as $moduleName) {
                $this->showSpecificModule($moduleName);
            }
            return self::SUCCESS;
        }

        $onlyEnabled = $this->option('enabled');
        if ($onlyEnabled) {
            return $this->showEnabledModules();
        }

        $onlyDisabled = $this->option('disabled');
        if ($onlyDisabled) {
            return $this->showDisabledModules();
        }

        $this->info('List of enabled modules:');
        $this->showEnabledModules();
        $this->line('');

        $this->info('List of disabled modules:');
        $this->showDisabledModules();
        $this->line('');

        return self::SUCCESS;
    }

    /**
     * @return int|void
     */
    private function showEnabledModules()
    {
        $enabledModules = $this->getEnabledModules();
        $enabledModuleNames = $enabledModules->getNames();

        if (count($enabledModuleNames) === 0) {
            $this->line('None');
            return self::FAILURE;
        }

        $this->line(join("\n",$enabledModuleNames));
    }

    /**
     * @return int|void
     */
    private function showDisabledModules()
    {
        $disabledModuleNames = $this->getDisabledModules();
        if (count($disabledModuleNames) === 0) {
            $this->line('None');
            return self::FAILURE;
        }

        $this->line(join("\n",$disabledModuleNames));
    }

    /**
     * @param $moduleName
     * @return void
     */
    private function showSpecificModule($moduleName): void
    {
        $allModules = $this->getAllModules();
        if (!in_array($moduleName, $allModules->getNames(),true)) {
            $this->line($moduleName . ' : <error>Module does not exist</error>');
            return;
        }

        $enabledModules = $this->getEnabledModules();
        if (in_array($moduleName,$enabledModules->getNames(),true)) {
            $this->line($moduleName . ' : <info>Module is enabled</info>');
            return;
        }

        $this->line($moduleName . ' : <info>Module is disabled</info>');

    }

    /**
     * @return FullModuleList
     */
    private function getAllModules() : FullModuleList
    {
        return App::make(FullModuleList::class);
    }

    /**
     * @return ModuleList
     */
    private function getEnabledModules() : ModuleList
    {
        return App::make(ModuleList::class);
    }

    /**
     * @return array
     */
    private function getDisabledModules(): array
    {
        return array_diff(
            $this->getAllModules()->getNames(),
            $this->getEnabledModules()->getNames()
        );
    }
}
