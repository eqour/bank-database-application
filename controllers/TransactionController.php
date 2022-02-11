<?php

namespace app\controllers;

use app\services\OperationService;

class TransactionController extends Controller {
    public function name(): string {
        return 'Transaction';
    }

    public function actionInfo(string $id = '') {
        $operationService = new OperationService();
        $operation = $operationService->findById($id);
        return $this->render('info', ['operation' => $operation]);
    }

    public function actionPreform() {
        return $this->render('preform');
    }
}
