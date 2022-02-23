<?php

namespace app\application\security;

use app\application\Application;

class CSRFTokenSource {
    private static ?string $_csrf = null;

    public static function getCSRFToken(): string {
        self::initIfRequired();
        return self::$_csrf;
    }

    public static function getCSRFTokenHash(): string {
        self::initIfRequired();
        return hash('sha256', self::$_csrf . Application::APPLICATION_SECRET);
    }

    public static function validateCSRFToken(string $token, string $hash): bool {
        return strcmp($hash, hash('sha256', $token . Application::APPLICATION_SECRET)) === 0;
    }

    private static function initIfRequired(): void {
        if (!isset(self::$_csrf)) {
            self::$_csrf = self::generateToken();
        }
    }

    private static function generateToken(int $length = 32): string {
        $cymbols = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        $result = '';
        for ($i = 0; $i < $length; $i++) {
            $result .= $cymbols[rand(0, $length - 1)];
        }
        return $result;
    }
}
