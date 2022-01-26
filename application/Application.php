<?php

namespace app\application;

use app\controllers\DefaultController;
use app\helpers\TextHelper;

class Application {
    public const APPLICATION_ROOT = __DIR__ . DIRECTORY_SEPARATOR . '..';

    public static int $maxRecordsPerPage = 10;
    public static \PDO $pdo;

    public static function run(): void {
        set_error_handler(function($errno, $errstr, $errfile, $errline) {
            throw new \ErrorException($errstr, $errno, 1, $errfile, $errline);
        });
        ob_start();
        try {
            self::requireFilesInDir(self::APPLICATION_ROOT);
            self::$pdo = new \PDO('mysql:host=localhost;dbname=bank', 'bank-user-value', 'bank-password-value');
            self::executeAction(RouteParser::parse());
        } catch (\Throwable $exception) {
            ob_end_clean();
            ob_start();
            throw new \Exception('Application error', 0, $exception);
        }
        ob_end_flush();
    }

    private static function executeAction(array $route): void {
        $getParameters = $_GET;
        unset($getParameters['route']);

        if (count($route) === 0) {
            (new DefaultController())->actionMain();
            return;
        } else if (count($route) === 2
                && isset($route[0])
                && isset($route[1])
                && is_string($route[0])
                && is_string($route[1])) {
            $controllerName = ucfirst(strtolower(TextHelper::convertDashToCamelCase($route[0]))) . 'Controller';
            $fullControllerName = 'app' . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR . $controllerName;
            $actionName = 'action' . ucfirst(strtolower($route[1]));
            if (class_exists($fullControllerName) && method_exists($fullControllerName, $actionName)) {
                $controller = new ($fullControllerName)();
                $controller->$actionName(...$getParameters);
                return;
            }
        }

        (new DefaultController())->actionError404();
    }

    private static function getFilesToFirstRequire(): array {
        return [
            self::APPLICATION_ROOT . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR . 'Controller.php'
        ];
    }

    private static function requireFilesInDir(string $directoryPath): void {
        foreach (self::getFilesToFirstRequire() as $filename) {
            require_once $filename;
        }
        $files = scandir($directoryPath);
        for ($i = 2; $i < count($files); $i++) {
            $fullPath = $directoryPath . DIRECTORY_SEPARATOR . $files[$i];
            if (is_dir($fullPath) && $files[$i] != 'views') {
                self::requireFilesInDir($fullPath);
            } else if(str_ends_with($files[$i], '.php')) {
                require_once $fullPath;
            }
        }
    }
}
