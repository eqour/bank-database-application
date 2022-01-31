<?php

namespace app\controllers;

use app\forms\CustomerSearchForm;
use app\services\ClientService;

class CustomerController extends Controller {
    public function name(): string {
        return 'Customer';
    }

    public function actionSearch() {
        $form = new CustomerSearchForm();
        if ($form->load($_POST) && $form->validate()) {
            if (null !== $client = (new ClientService())->findByPassport($form->passport)) {
                return $this->redirect(DIRECTORY_SEPARATOR . 'customer' . DIRECTORY_SEPARATOR . 'info', ['id' => $client->id]);
            } else {
                return $this->render('search', ['form' => $form, 'doesNotExist' => true]);
            }
        }
        return $this->render('search', ['form' => $form]);
    }

    public function actionInfo() {
        return $this->render('info');
    }
}
