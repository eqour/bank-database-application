<?php

namespace app\controllers;

class CustomerController extends Controller {
    public function name(): string {
        return 'Customer';
    }

    public function actionSearch() {
        return $this->render('search');
    }

    public function actionInfo() {
        return $this->render('info');
    }
}
