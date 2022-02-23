<?php

use app\application\Application;

header('Cache-Control: no-cache, no-store, must-revalidate');

require '..' . DIRECTORY_SEPARATOR . 'application' . DIRECTORY_SEPARATOR . 'Application.php';
Application::run();
