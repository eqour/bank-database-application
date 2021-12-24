<?php

namespace app\application;

class RouteParser {
    public static function parse(): array {
        if (isset($_GET['route'])) {
            return explode('/', trim($_GET['route'], '/'));
        } else {
            return [];
        }
    }
}
