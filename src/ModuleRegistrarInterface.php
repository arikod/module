<?php

namespace Arikod\Module;

interface ModuleRegistrarInterface
{
    /**
     * @return array
     */
    public function getModules(): array;

    /**
     * @param $moduleName
     * @return mixed|null
     */
    public function getModule($moduleName);
}
