<?php

namespace app\controllers;

use app\application\Application;
use app\helpers\TextHelper;

class Controller {
    public function name(): string {
        throw new \Exception('Method "name" not implemented');
    }

    protected function render(string $view, array $params = []): void {
        extract($params, EXTR_SKIP);
        ob_start();
        include(Application::APPLICATION_ROOT . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . TextHelper::convertCamelCaseToDash($this->name()) . DIRECTORY_SEPARATOR . $view . '.php');
        $content = ob_get_contents();
        ob_end_clean();
        $this->renderLayout('main', ['content' => $content]);
    }

    private function renderLayout(string $layout, array $params = []): void {
        extract($params, EXTR_SKIP);
        ob_start();
        include(Application::APPLICATION_ROOT . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'layout' . DIRECTORY_SEPARATOR . $layout . '.php');
    }
}
