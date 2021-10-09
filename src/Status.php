<?php

namespace Arikod\Module;

use Illuminate\Config\Repository;
use Illuminate\Filesystem\Filesystem;

class Status
{
    /**
     * @var ModuleList\Loader
     */
    private $loader;

    /**
     * @var ModuleList
     */
    private $list;

    /**
     * @var Repository
     */
    private $config;

    /**
     * @var Filesystem
     */
    private $files;

    public function __construct(
        ModuleList\Loader $loader,
        ModuleList $list,
        Repository $config,
        Filesystem $files
    )
    {
        $this->loader = $loader;
        $this->list = $list;
        $this->config = $config;
        $this->files = $files;
    }

    /**
     * Get a list of modules that will be changed
     *
     * @param bool $isEnabled
     * @param string[] $modules
     * @return string[]
     */
    public function getModulesToChange(bool $isEnabled, array $modules): array
    {
        $changed = [];
        foreach ($this->getAllModules($modules) as $name) {
            $currentStatus = $this->list->has($name);
            if (in_array($name, $modules)) {
                if ($isEnabled != $currentStatus) {
                    $changed[] = $name;
                }
            }
        }
        return $changed;
    }

    /**
     * Gets all modules and filters against the specified list
     *
     * @param string[] $modules
     * @return string[]
     * @throws \LogicException
     */
    private function getAllModules(array $modules): array
    {
        $all = $this->loader->load();
        $unknown = [];
        foreach ($modules as $name) {
            if (!isset($all[$name])) {
                $unknown[] = $name;
            }
        }
        if ($unknown) {
            throw new \LogicException("Unknown module(s): '" . implode("', '", $unknown) . "'");
        }
        return array_keys($all);
    }

    /**
     * Sets specified modules to enabled or disabled state
     *
     * Performs other necessary routines, such as cache cleanup
     *
     * @param bool $isEnabled
     * @param string[] $modules
     * @return void
     */
    public function setIsEnabled(bool $isEnabled, array $modules)
    {
        $result = [];
        foreach ($this->getAllModules($modules) as $name) {
            $currentStatus = $this->list->has($name);
            if (in_array($name, $modules)) {
                $result[$name] = (int)$isEnabled;
            } else {
                $result[$name] = (int)$currentStatus;
            }
        }

        $config = $this->getConfig();

        $this->config->set('arikod', array_merge($config, ['modules' => $result]));
        $this->files->put(config_path('arikod.php'),'<?php return ' . var_export($this->getConfig(), true) . ';');
    }

    /**
     * @return array|mixed
     */
    public function getConfig()
    {
        return $this->config->get('arikod');
    }
}
