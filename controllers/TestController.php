<?php

namespace app\controllers;

use app\application\Application;

class TestController extends Controller {
    public function name(): string {
        return 'Test';
    }

    public function actionExamplequery() {
        $result = Application::$pdo->query("SELECT * FROM `client`;")->fetchAll();
        return $this->render('example-query', ['clients' => $result]);
    }
}