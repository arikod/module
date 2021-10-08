<?php

namespace Arikod\Module;

use Illuminate\Support\ServiceProvider;

class ModuleRegistrar implements ModuleRegistrarInterface
{
    /**
     * @var array
     */
    private static $modules = [];

    /**
     * @param string $moduleName
     * @param string $serviceProvider
     */
    public static function register(string $moduleName, string $serviceProvider)
    {
        self::validateServiceProvider($serviceProvider);

        if (isset(self::$modules[$moduleName])) {
            throw new \LogicException($moduleName . ' has been already defined.');
        }
        self::$modules[$moduleName] = $serviceProvider;
    }

    /**
     * @return array
     */
    public function getModules(): array
    {
        return self::$modules;
    }

    /**
     * @param $moduleName
     * @return mixed|null
     */
    public function getModule($moduleName)
    {
        return self::$modules[$moduleName] ?? null;
    }

    /**
     * @param string $provider
     */
    private static function validateServiceProvider(string $provider)
    {
        if (!is_subclass_of($provider,ServiceProvider::class)) {
            throw new \LogicException($provider . ' is not a valid service provider');
        }
    }
}
