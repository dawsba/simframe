<?php

namespace Engine\Config;

spl_autoload_register(function ($name) {
    include 'Src/' . $name . '.php';
});

require_once "./vendor/autoload.php";