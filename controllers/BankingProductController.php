<?php

namespace app\controllers;

use app\services\ServiceTypeService;

class BankingProductController extends Controller {
    public function name(): string {
        return 'BankingProduct';
    }

    public function actionSearch() {
        return $this->render('search');
    }

    public function actionAll() {
        $service = new ServiceTypeService();
        $products = $service->findAll();
        return $this->render('all', ['products' => $products]);
    }
    
    public function actionInfo() {
        return $this->render('info');
    }
    
    public function actionRegistration() {
        return $this->render('registration');
    }
}
