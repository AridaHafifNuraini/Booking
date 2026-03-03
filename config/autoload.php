<?php
spl_autoload_register(function($class) {
    $paths = [
        __DIR__ . '/../app/models/' . $class . '.php',
        __DIR__ . '/../app/controllers/' . $class . '.php'
    ];
    foreach ($paths as $file) {
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});
require_once __DIR__ . '/Database.php';

?>