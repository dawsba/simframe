<?php

namespace Engine\Config;

ini_set('opcache.enable', 0);
opcache_reset();

use Engine\Application;
use Engine\Services\Database\Pdo;
use Engine\Services\Events\Events;
use Engine\Services\Twig\Twig;

require_once "autoload.php";

$app = new Application();

// REGISTER SERVICES

//Database Service
$app->register(
    'db',
    new Pdo($app->getConfig()->getConfigKey('db'))
);

// TWIG service
$app->register(
    'twig',
    new Twig(array_pop($app->getConfig()->getConfigKey('twig')))
);

// Event Manager service
$app->register(
    'event',
    new Events($app->log)
);



$app->log->info('Booting complete');
