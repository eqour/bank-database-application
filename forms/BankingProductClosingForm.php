<?php

namespace app\forms;

class BankingProductClosingForm extends Form {
	public string $accountNumber;

	protected function fieldNames(): array {
		return array_merge(parent::fieldNames(), [
			'accountNumber'
		]);
	}

	protected function validateFields(): void {
        if (!isset($this->accountNumber) || trim($this->accountNumber) === '') {
            $this->addError('accountNumber', 'Заполните поле');
        } else if (1 !== preg_match('/^[0-9]{20}$/', $this->accountNumber)) {
            $this->addError('accountNumber', 'Используйте только цифры. Длина номера: 20 символов.');
        }
    }
}
