<?php

use app\application\Application;
use app\services\CapitalizationService;

require '..' . DIRECTORY_SEPARATOR . 'application' . DIRECTORY_SEPARATOR . 'Application.php';

Application::init();
CapitalizationService::executeCapitalization();
