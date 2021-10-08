<?php

namespace Arikod\Module;

use Arikod\Module\Console\ModuleStatusCommand;
use Illuminate\Support\ServiceProvider;

class ModuleServiceProvider extends ServiceProvider
{
    public function boot(ModuleList $moduleList)
    {
        foreach ($moduleList->getAll() as $provider) {
            $this->app->register($provider);
        }

        // Register the command if we are using the application via the CLI
        if ($this->app->runningInConsole()) {
            $this->commands([
                ModuleStatusCommand::class
            ]);
        }
    }

    public function register()
    {
        $this->app->bind(ModuleRegistrarInterface::class, ModuleRegistrar::class);
        $this->app->when(ModuleList::class)
            ->needs('$config')
            ->giveConfig('arikod.modules');
    }
}
