<?php

namespace Arikod\Module\Console;

use Arikod\Module\FullModuleList;
use Arikod\Module\Status;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;

abstract class AbstractModuleManageCommand extends Command
{
    /**
     * Names of input arguments or options
     */
    const INPUT_KEY_ALL = 'all';

    /**
     * Names of input arguments or options
     */
    const INPUT_KEY_MODULES = 'module';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $isEnable = $this->isEnable();
        if ($this->option(self::INPUT_KEY_ALL)) {
            /** @var FullModuleList $fullModuleList */
            $fullModuleList = App::make(FullModuleList::class);
            $modules = $fullModuleList->getNames();
        } else {
            $modules = $this->argument(self::INPUT_KEY_MODULES);
        }
        $messages = $this->validate($modules);

        if (!empty($messages)) {
            $this->line(implode(PHP_EOL, $messages));
            // we must have an exit code higher than zero to indicate something was wrong
            return self::FAILURE;
        }
        try {
            $modulesToChange = $this->getStatus()->getModulesToChange($isEnable, $modules);
        }catch (\LogicException $e) {
            $this->line('<error>' . $e->getMessage() . '</error>');
            return self::FAILURE;
        }

        if (!empty($modulesToChange)) {
            $this->setIsEnabled($isEnable, $modulesToChange);
        } else {
            $this->line('<info>No modules were changed.</info>');
        }

        return self::SUCCESS;
    }

    /**
     * Enable/disable modules
     *
     * @param bool $isEnable
     * @param string[] $modulesToChange
     * @return void
     */
    public function setIsEnabled(bool $isEnable, array $modulesToChange)
    {
        $this->getStatus()->setIsEnabled($isEnable, $modulesToChange);
        if ($isEnable) {
            $this->line('<info>The following modules have been enabled:</info>');
        } else {
            $this->line('<info>The following modules have been disabled:</info>');
        }
        $this->line('<info>- ' . implode("\n- ", $modulesToChange) . '</info>');
        $this->line('');
    }


    /**
     * Get module status
     *
     * @return Status
     */
    public function getStatus() : Status
    {
        return App::make(Status::class);
    }

    /**
     * Is it "enable" or "disable" command
     *
     * @return bool
     */
    abstract protected function isEnable(): bool;

    /**
     * Validate list of modules and return error messages
     *
     * @param string[] $modules
     * @return string[]
     */
    protected function validate(array $modules): array
    {
        $messages = [];
        if (empty($modules)) {
            $messages[] = '<error>No modules specified. Specify a space-separated list of modules' .
                ' or use the --all option</error>';
        }
        return $messages;
    }
}
