<?php

namespace app\forms;

class TransactionPreformForm extends Form {
    public $accountNumber;
    public $amount;
    public $description;

    protected function fieldNames(): array {
        return [
            'accountNumber',
            'amount',
            'description'
        ];
    }

    protected function validateFields(): void {
        return;
    }
}
