<?php

namespace app\forms;

class BankingProductSearchForm extends Form {
    public $accountNumber;

    protected function fieldNames(): array {
        return [
            'accountNumber'
        ];
    }

    protected function validateFields(): void {
        if (!isset($this->accountNumber) || trim($this->accountNumber) === '') {
            $this->addError('accountNumber', 'Заполните поле');
        } else if (1 !== preg_match('/^[0-9]{20}$/', $this->accountNumber)) {
            $this->addError('accountNumber', 'Используйте только цифры. Длина номера: 20 символов.');
        }
    }
}
