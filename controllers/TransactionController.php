<?php

namespace app\controllers;

use app\forms\TransactionPreformForm;
use app\services\OperationService;
use app\services\ServiceService;

class TransactionController extends Controller {
    public function name(): string {
        return 'Transaction';
    }

    public function actionInfo(string $id = '') {
        $operationService = new OperationService();
        $operation = $operationService->findById($id);
        return $this->render('info', ['operation' => $operation]);
    }

    public function actionPreform(string $account = '') {
        $serviceService = new ServiceService();
        $service = $serviceService->findByAccountNumber($account);
        if (!isset($service)) {
            $this->redirect(DIRECTORY_SEPARATOR);
        }
        $form = new TransactionPreformForm();
        $form->accountNumber = $service->account_number;
        return $this->render('preform', ['form' => $form]);
    }
}
