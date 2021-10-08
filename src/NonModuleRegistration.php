<?php

namespace Arikod\Module;

use RuntimeException;

(static function (): void {
    $baseDir = \dirname(__DIR__, 4) . '/';

    $configFilePath = $baseDir . '/config/arikod.php';

    if (file_exists($configFilePath)) {
        $globPatterns = require $configFilePath;

        foreach ($globPatterns['glob'] as $globPattern) {
            // Sorting is disabled intentionally for performance improvement
            $files = \glob($baseDir . $globPattern, GLOB_NOSORT);
            if ($files === false) {
                throw new RuntimeException("glob(): error with '$baseDir$globPattern'");
            }

            \array_map(
                static function (string $file): void {
                    require_once $file;
                },
                $files
            );
        }
    }

})();
