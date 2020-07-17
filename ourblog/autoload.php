<?php

spl_autoload_register(function ($class) {
    $path = __DIR__ . '/lib/' . str_replace('_', '/', $class) . '.php';
    if (file_exists($path)) {
        include $path;
    }
}, true, true);
