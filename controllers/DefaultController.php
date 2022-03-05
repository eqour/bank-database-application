<?php

namespace app\controllers;

class DefaultController extends Controller {
    public function name(): string {
        return 'Default';
    }

    public function actionMain() {
        return $this->render('main');
    }

    public function actionError404() {
        header("HTTP/1.1 404 Not Found");
        return $this->render('error', ['error' => 'Ошибка 404. Страница не найдена']);
    }

    public function actionError500() {
        header("HTTP/1.1 500 Internal Server Error");
        return $this->render('error', ['error' => 'Ошибка 500. Внутренняя ошибка сервера']);
    }
}
