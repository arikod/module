<?php

namespace Arikod\Module;

use Arikod\Module\Console\ModuleDisableCommand;
use Arikod\Module\Console\ModuleEnableCommand;
use Arikod\Module\Console\ModuleStatusCommand;
use Illuminate\Support\ServiceProvider;

class ModuleServiceProvider extends ServiceProvider
{
    // OverEngineering
    public function boot(ModuleList $moduleList)
    {
        foreach ($moduleList->getAll() as $provider) {
            $this->app->register($provider);
        }

        $this->registerCommands();
        $this->registerPublishing();
    }

    public function register()
    {
        $modules = config('arikod.modules') ?? [];

        $this->app->bind(ModuleRegistrarInterface::class, ModuleRegistrar::class);
        $this->app->when(ModuleList::class)
            ->needs('$config')
            ->give($modules);
    }

    public function registerCommands()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                ModuleStatusCommand::class,
                ModuleEnableCommand::class,
                ModuleDisableCommand::class
            ]);
        }
    }

    public function registerPublishing()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/arikod.php' => $this->app->configPath('arikod.php'),
            ], 'arikod-config');
        }
    }
}
