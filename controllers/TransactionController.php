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
        $operationRejected = null;
        
        if ($form->load($_POST) && $form->validate()) {
            if (!isset($service->actual_close_date)) {
                $operationService = new OperationService();
                $operationService->preform($form->accountNumber, $form->floatAmount, $form->description);
                return $this->redirect(DIRECTORY_SEPARATOR . 'banking-product' . DIRECTORY_SEPARATOR . 'info', ['account' => $account]);
            } else {
                $operationRejected = true;
            }
        }

        $form->accountNumber = $service->account_number;

        return $this->render('preform', [
            'form' => $form,
            'operationRejected' => $operationRejected
        ]);
    }
}
