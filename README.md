## Module


### Todo List
    ModuleFacade

## Install
To install through Composer, by run the following command:

``` bash
composer require arikod/module
```

The package will automatically register a service provider and alias.

Optionally, publish the package's configuration file by running:

``` bash
php artisan vendor:publish --provider="Arikod\Module\ModuleServiceProvider"
```

## Register module
Create registration.php right next to composer.json

```php
<?php

use Arikod\Module\ModuleRegistrar;

ModuleRegistrar::register(
    'Arikod_TestModule', // --> ModuleName
    \Arikod\TestModule\TestModuleServiceProvider::class // ServiceProvider
);
```

## Enable / Disable Module
config/arikod.php

```php
return [
    'modules' => [
        'Arikod_TestModule' => 1, // Enabled,
        'Arikod_ModuleAA' => 0    // Disabled
        // Arikod_ModuleBB // Disabled
    ],

    'glob' => [
        'lib/*/*/registration.php',
        'vendor/*/*/registration.php'
    ],
];
```

## Usage
```php 
<?php

namespace App\Http\Controllers;

use Arikod\Module\FullModuleList;
use Arikod\Module\ModuleList;

class ModuleController extends Controller
{
    /**
     * @var ModuleList
     */
    protected $moduleList;

    /**
     * @var FullModuleList
     */
    protected $fullModuleList;

    public function __construct(ModuleList $moduleList, FullModuleList $fullModuleList)
    {
        $this->moduleList = $moduleList;
        $this->fullModuleList = $fullModuleList;
    }

    public function index()
    {
        // All Modules
        $fullModuleList = $this->fullModuleList;
        echo '<pre>',print_r($fullModuleList->getAll()),'</pre>';
        echo '<pre>',print_r($fullModuleList->getNames()),'</pre>';

        // Just Enabled Modules [config.php]
        $moduleList = $this->moduleList;
        echo '<pre>',print_r($moduleList->getAll()),'</pre>';
        echo '<pre>',print_r($moduleList->getNames()),'</pre>';
    }
}
```



## Commands
``` bash
php artisan module:status
php artisan module:status Arikod_TestModule
php artisan module:status Arikod_ModuleA Arikod_ModuleB
```

``` bash
php artisan module:enable --all
php artisan module:enable Module_AA Module_BB

php artisan module:disable --all
php artisan module:disable Module_AA Module_BB
```



## TestModule
To install through Composer, by run the following command:

``` bash
composer require arikod/test-module
```

## Credits
- [Bekir Gülmüş](https://github.com/bekirgulmus)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
