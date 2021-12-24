<?php

namespace app\controllers;

class DefaultController extends Controller {
    public function name(): string {
        return 'Default';
    }

    public function actionMain() {
        return $this->render('main');
    }
}
