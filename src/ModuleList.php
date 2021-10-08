<?php

namespace Arikod\Module;

class ModuleList
{
    /**
     * @var array
     */
    private $config;

    /**
     * @var ModuleList\Loader
     */
    private $loader;

    /**
     * An associative array of modules
     *
     * The possible values are 1 (enabled) or 0 (disabled)
     *
     * @var int[]
     */
    private $configData;

    /**
     * Enumeration of the enabled module names
     *
     * @var string[]
     */
    private $enabled;

    public function __construct(array $config, ModuleList\Loader $loader)
    {
        $this->config = $config;
        $this->loader = $loader;
    }

    /**
     * @return array|string[]
     */
    public function getAll(): array
    {
        if (null === $this->enabled) {
            $all = $this->loader->load();
            if (empty($all)) {
                return []; // don't record erroneous value into memory
            }
            $this->enabled = [];
            foreach ($all as $key => $value) {
                if ($this->has($key)) {
                    $this->enabled[$key] = $value;
                }
            }
        }
        return $this->enabled;
    }

    /**
     * @param $name
     * @return string|null
     */
    public function getOne($name): ?string
    {
        $enabled = $this->getAll();
        return $enabled[$name] ?? null;
    }

    /**
     * @return array|int[]|string[]
     */
    public function getNames(): array
    {
        $this->loadConfigData();
        if (!$this->configData) {
            return [];
        }
        return array_keys(array_filter($this->configData));
    }

    /**
     * @param $name
     * @return bool
     */
    public function has($name): bool
    {
        $this->loadConfigData();
        if (!$this->configData) {
            return false;
        }
        return !empty($this->configData[$name]);
    }

    /**
     * Loads configuration data only
     *
     * @return void
     */
    private function loadConfigData()
    {
        if (null === $this->configData && null !== $this->config) {
            $this->configData = $this->config;
        }
    }
}
