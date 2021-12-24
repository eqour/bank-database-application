<?php

namespace app\controllers;

use app\application\Application;

class Controller {
    public function name(): string {
        throw new \Exception('Method "name" not implemented');
    }

    protected function render(string $view, array $params = []): void {
        extract($params, EXTR_SKIP);
        include(Application::APPLICATION_ROOT . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . strtolower($this->name()) . DIRECTORY_SEPARATOR . $view . '.php');
    }
}
