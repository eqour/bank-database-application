<?php

use app\application\Application;
use app\services\CapitalizationService;

require '..' . DIRECTORY_SEPARATOR . 'application' . DIRECTORY_SEPARATOR . 'Application.php';

$config = require '..'. DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php';
$app = new Application($config);
CapitalizationService::executeCapitalization();
