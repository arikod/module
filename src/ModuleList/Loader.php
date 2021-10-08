<?php

namespace Arikod\Module\ModuleList;

use Arikod\Module\ModuleRegistrarInterface;

class Loader
{
    /**
     * @var ModuleRegistrarInterface
     */
    protected $moduleRegistrar;

    /**
     * @param ModuleRegistrarInterface $moduleRegistrar
     */
    public function __construct(ModuleRegistrarInterface $moduleRegistrar)
    {
        $this->moduleRegistrar = $moduleRegistrar;
    }

    /**
     * @return array
     */
    public function load(): array
    {
        return $this->moduleRegistrar->getModules();
    }
}
