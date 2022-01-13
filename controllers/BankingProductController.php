<?php

namespace app\controllers;

class BankingProductController extends Controller {
    public function name(): string {
        return 'BankingProduct';
    }

    public function actionSearch() {
        return $this->render('search');
    }
    
    public function actionInfo() {
        return $this->render('info');
    }
}
