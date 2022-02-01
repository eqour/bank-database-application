<?php

namespace app\controllers;

use app\forms\CustomerSearchForm;
use app\services\CustomerService;

class CustomerController extends Controller {
    public function name(): string {
        return 'Customer';
    }

    public function actionSearch() {
        $form = new CustomerSearchForm();
        if ($form->load($_POST) && $form->validate()) {
            if (null !== $customer = (new CustomerService())->findByPassport($form->passport)) {
                return $this->redirect(DIRECTORY_SEPARATOR . 'customer' . DIRECTORY_SEPARATOR . 'info', ['id' => $customer->id]);
            } else {
                return $this->render('search', ['form' => $form, 'doesNotExist' => true]);
            }
        }
        return $this->render('search', ['form' => $form]);
    }

    public function actionInfo(string $id = '') {
        $service = new CustomerService();
        $customer = $service->findById($id);
        if (!isset($customer)) {
            return $this->redirect('/customer/search');
        }
        return $this->render('info', ['customer' => $customer]);
    }
}
