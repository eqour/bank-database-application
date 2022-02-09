<?php

namespace app\controllers;

use app\forms\BankingProductSearchForm;
use app\services\ServiceService;
use app\services\ServiceTypeService;

class BankingProductController extends Controller {
    public function name(): string {
        return 'BankingProduct';
    }

    public function actionSearch() {
        $form = new BankingProductSearchForm();
        if ($form->load($_POST) && $form->validate()) {
            if (null !== $bankingProduct = (new ServiceService())->findByAccountNumber($form->accountNumber)) {
                return $this->redirect(DIRECTORY_SEPARATOR . 'banking-product' . DIRECTORY_SEPARATOR . 'info', ['id' => $bankingProduct->account_number]);
            } else {
                return $this->render('search', ['form' => $form, 'doesNotExist' => true]);
            }
        }
        return $this->render('search', ['form' => $form]);
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
