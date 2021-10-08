<?php

namespace Arikod\Module;

class FullModuleList
{
    /**
     * @var ModuleList\Loader
     */
    private $loader;

    /**
     * @var
     */
    private $data;

    public function __construct(ModuleList\Loader $loader)
    {
        $this->loader = $loader;
    }

    /**
     * @return array
     */
    public function getAll(): array
    {
        if (null === $this->data) {
            $this->data = $this->loader->load();
        }
        return $this->data;
    }

    /**
     * @param $name
     * @return mixed|null
     */
    public function getOne($name)
    {
        $data = $this->getAll();
        return $data[$name] ?? null;
    }

    /**
     * @return int[]|string[]
     */
    public function getNames(): array
    {
        $data = $this->getAll();
        return array_keys($data);
    }

    /**
     * @param $name
     * @return bool
     */
    public function has($name): bool
    {
        $this->getAll();
        return isset($this->data[$name]);
    }
}
