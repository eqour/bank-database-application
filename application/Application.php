<?php

namespace app\application;

class Application {
    public const APPLICATION_ROOT = __DIR__ . DIRECTORY_SEPARATOR . '..';

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
        if (count($route) == 2) {
            $controllerName = ucfirst(strtolower($route[0])) . 'Controller';
            $actionName = 'action' . ucfirst(strtolower($route[1]));
        } else {
            $controllerName = 'DefaultController';
            $actionName = 'actionMain';
        }
        $controller = new ('app' . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR . $controllerName)();
        $getParameters = $_GET;
        unset($getParameters['route']);
        $controller->$actionName(...$getParameters);
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
