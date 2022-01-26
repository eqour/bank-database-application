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

    public function actionAll(int $p = 0) {
        $service = new ServiceTypeService();
        $helper = $service->getPaginationHelper($p);
        $products = $service->findAllInRange($helper->getStartRecordIndex(), $helper->getEndRecordIndex());
        return $this->render('all', ['products' => $products, 'paginationHelper' => $helper]);
    }
    
    public function actionInfo() {
        return $this->render('info');
    }
    
    public function actionRegistration() {
        return $this->render('registration');
    }
}
