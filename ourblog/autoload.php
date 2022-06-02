<?php

spl_autoload_register(function ($class) {
    $path = __DIR__ . '/lib/' . str_replace('_', '/', $class) . '.php';
    if (defined('__BPC__')) {
        include_once_silent($path);
    } else {
        if (file_exists($path)) {
            include $path;
        }
    }
}, true, true);
