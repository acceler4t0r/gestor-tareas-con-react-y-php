<?php

spl_autoload_register(function ($class) {
    $prefixes = [
        'App\\'    => __DIR__ . '/App',
        'Vendor\\' => __DIR__ . '/Vendor',
    ];

    foreach ($prefixes as $prefix => $baseDir) {
        if (strncmp($prefix, $class, strlen($prefix)) !== 0) {
            continue;
        }

        $relativeClass = substr($class, strlen($prefix));
        $relativePath  = str_replace('\\', '/', $relativeClass) . '.php';

        $file = $baseDir . $relativePath;

        if (file_exists($file)) {
            require $file;
            return;
        }
    }
});
