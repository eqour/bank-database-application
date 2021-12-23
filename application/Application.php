<?php

namespace app\application;

class Application {
    public const APPLICATION_ROOT = __DIR__ . DIRECTORY_SEPARATOR . '..';

    public static \PDO $pdo;

    public static function Run(): void {
        self::RequireFilesInDir(self::APPLICATION_ROOT);
        self::$pdo = new \PDO('mysql:host=localhost;dbname=bank', 'bank-user-value', 'bank-password-value');
        $route = RouteParser::parse();
    }

    private static function RequireFilesInDir(string $directoryPath): void {
        $files = scandir($directoryPath);
        for ($i = 2; $i < count($files); $i++) {
            $fullPath = $directoryPath . DIRECTORY_SEPARATOR . $files[$i];
            if (is_dir($fullPath)) {
                self::RequireFilesInDir($fullPath);
            } else if(str_ends_with($files[$i], '.php')) {
                require_once $fullPath;
            }
        }
    }
}
