<?php

namespace app\application;

class RouteParser {
    public static function parse() {
        return explode('/', trim($_GET['route'], '/'));
    }
}
