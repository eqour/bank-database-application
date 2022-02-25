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
        include(Application::$root . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . TextHelper::convertCamelCaseToDash($this->name()) . DIRECTORY_SEPARATOR . $view . '.php');
        $content = ob_get_contents();
        ob_end_clean();
        $this->renderLayout('main', ['content' => $content, 'js' => $this->renderIncludeJsCode()]);
    }

    private function renderLayout(string $layout, array $params = []): void {
        extract($params, EXTR_SKIP);
        ob_start();
        include(Application::$root . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'layout' . DIRECTORY_SEPARATOR . $layout . '.php');
    }

    private $jsFileNamesToRequire = [];
    private function registerJsFile(string $fileName): void {
        $this->jsFileNamesToRequire[] = $fileName;
    }

    private function renderIncludeJsCode(): string {
        $includeString = '';
        foreach ($this->jsFileNamesToRequire as $fileName) {
            $includeString .= '<script src="' . DIRECTORY_SEPARATOR . 'js' . DIRECTORY_SEPARATOR . TextHelper::convertCamelCaseToDash($this->name()) . DIRECTORY_SEPARATOR . $fileName . '.js"></script>' . "\n";
        }
        return $includeString;
    }

    protected function redirect(string $path, array $params = []): void {
        header('Location: ' . $path . TextHelper::paramsToQuery($params));
    }
}
