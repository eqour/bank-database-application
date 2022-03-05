<?php

use app\application\Application;

// define('APP_DEBUG', true);

header('Cache-Control: no-cache, no-store, must-revalidate');

require '..' . DIRECTORY_SEPARATOR . 'application' . DIRECTORY_SEPARATOR . 'Application.php';
$config = require '..'. DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php';
$app = new Application($config);
$app->run();
