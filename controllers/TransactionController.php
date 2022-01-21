<?php

namespace app\controllers;

class TransactionController extends Controller {
    public function name(): string {
        return 'Transaction';
    }

    public function actionInfo() {
        return $this->render('info');
    }

    public function actionPreform() {
        return $this->render('preform');
    }
}
