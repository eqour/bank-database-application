<?php

namespace app\controllers;

use app\forms\CustomerSearchForm;
use app\services\CustomerService;
use app\services\ServiceService;

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

    public function actionInfo(string $id = '', int $p = 0) {
        $customer = (new CustomerService())->findById($id);
        $services = (new ServiceService())->findAllByCustomerIdForCustomer($id);
        $helper = (new ServiceService())->getPaginationHelper($p, $customer->id);
        if (!isset($customer)) {
            return $this->redirect('/customer/search');
        }
        return $this->render('info', ['customer' => $customer, 'services' => $services, 'paginationHelper' => $helper, 'appendParams' => ['id' => $id]]);
    }
}
