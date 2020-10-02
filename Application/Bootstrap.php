<?php

// Load Configurations
require_once "Configurations/Global.php";
require_once "Configurations/Security.php";
require_once "Configurations/Database.php";
require_once "Configurations/User.php";
require_once "Configurations/Route.php";

// Load Classes
spl_autoload_register(function ($class) {
    $file = str_replace("\\", "/", $class);
    if (file_exists("../Application/{$file}.php")) {
        require_once "{$file}.php";
    }
});
