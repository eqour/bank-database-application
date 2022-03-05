<?php

namespace app\application;

use app\application\security\CSRFTokenSource;
use app\controllers\DefaultController;
use app\helpers\TextHelper;

class Application {
    public static string $root = __DIR__ . DIRECTORY_SEPARATOR . '..';
    public static string $secret = '4i6jhy1ufu6q3f6zh4knxmc6frsieyh9';
    public static int $maxRecordsPerPage = 5;
    public static \PDO $pdo;
    public static CSRFTokenSource $csrfTokenSource;

    private array $config;
    private array $priorityFiles;
    private array $ignoredDirectories;

    public function __construct(array $config) {
        try {
            $this->config = $config;
            $this->init();
        } catch (\Throwable $exception) {
            $this->processException($exception);
        }
    }

    private function init(): void {
        self::$root = $this->config['root'];
        self::$secret = $this->config['security']['secret'];
        self::$maxRecordsPerPage = $this->config['pagination']['max-records-per-page'];
        $this->priorityFiles = $this->preparePaths($this->config['include']['first']);
        $this->ignoredDirectories = $this->preparePaths($this->config['include']['ignore']);
        $this->requirePriorityFiles();
        $this->requireFilesInDir(self::$root);
        self::$pdo = new \PDO($this->config['db']['dsn'], $this->config['db']['login'], $this->config['db']['password']);
        self::$csrfTokenSource = new CSRFTokenSource();
    }

    public function run(): void {
        set_error_handler(function($errno, $errstr, $errfile, $errline) {
            throw new \ErrorException($errstr, $errno, 1, $errfile, $errline);
        });
        ob_start();
        try {
            setcookie('csrf', self::$csrfTokenSource::getCSRFToken(), [
                'httponly' => true,
                'samesite' => 'Strict'
            ]);
            $this->executeAction(RouteParser::parse());
        } catch (\Throwable $exception) {
            $this->processException($exception);
        }
        ob_end_flush();
    }

    private function executeAction(array $route): void {
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

    private function preparePaths(array $paths): array {
        $preparedPaths = [];
        foreach ($paths as $path) {
            $preparedPaths[] = $this->preparePath($path);
        }
        return $preparedPaths;
    }

    private function preparePath(string $path): string {
        return str_replace([
            '@root'
        ], [
            self::$root
        ], $path);
    }

    private function requirePriorityFiles(): void {
        foreach ($this->priorityFiles as $filename) {
            require_once $filename;
        }
    }

    private function requireFilesInDir(string $directoryPath): void {
        $files = scandir($directoryPath);
        for ($i = 2; $i < count($files); $i++) {
            $fullPath = $directoryPath . DIRECTORY_SEPARATOR . $files[$i];
            if (is_dir($fullPath) && !in_array($fullPath, $this->ignoredDirectories)) {
                $this->requireFilesInDir($fullPath);
            } else if(str_ends_with($files[$i], '.php')) {
                require_once $fullPath;
            }
        }
    }

    private function processException($exception): void {
        ob_end_clean();
        ob_start();
        if (defined('APP_DEBUG')) {
            throw new \Exception('Application error', 0, $exception);
        } else {
            (new DefaultController())->actionError500();
        }
    }
}
