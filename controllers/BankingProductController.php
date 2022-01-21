<?php

namespace app\controllers;

class BankingProductController extends Controller {
    public function name(): string {
        return 'BankingProduct';
    }

    public function actionSearch() {
        return $this->render('search');
    }

    public function actionAll() {
        return $this->render('all');
    }
    
    public function actionInfo() {
        return $this->render('info');
    }
    
    public function actionRegistration() {
        return $this->render('registration');
    }
}
