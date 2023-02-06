<?php

spl_autoload_register(function ($class) {
    // Define the base directory for the namespace prefix
    $base_dir = __DIR__ . '/' ;

    // Replace the namespace prefix with the base directory, replace namespace
    // separators with directory separators in the relative class name, append
    // with .php
    $file = $base_dir . str_replace('\\', '/', $class) . '.php';

    // If the file exists, require it
    if (file_exists($file)) {
        require $file;
    }
});

