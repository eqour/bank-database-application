<?php

namespace app\forms;

class TransactionPreformForm extends Form {
    public $accountNumber;
    public $amount;
    public $description;

    public float $floatAmount;

    protected function fieldNames(): array {
        return [
            'accountNumber',
            'amount',
            'description'
        ];
    }

    protected function validateFields(): void {
        if (!isset($this->accountNumber) || 1 !== preg_match('/^[0-9]{20}$/', $this->accountNumber)) {
            $this->addError('accountNumber', 'Заполните поле');
        }

        if (isset($this->description)) {
            $this->description = trim($this->description);
            if ($this->description === '') {
                $this->description = null;
            } else if (1 !== preg_match('/^[A-Za-zА-Яа-яЁё0-9 -]{1,100}$/u', $this->accountNumber)) {
                $this->addError('description', 'Используйте символы русского и английского алфавита, цифры, а также дефис');
            }
        }

        if (!isset($this->amount)) {
            $this->addError('amount', 'Заполните поле');
        }
        
        if (1 !== preg_match('/^[0-9,.-]{1,16}$/', $this->amount)) {
            $this->addError('amount', 'Введите корректное значение');
        }

        $amountString = $this->amount;
        $amountString = str_replace(',', '.', $amountString);
        $floatParts = explode('.', $amountString);

        if (isset($floatParts[1]) && mb_strlen($floatParts[1]) > 2) {
            $this->addError('amount', 'Не более двух заков после запятой');
        }

        $this->floatAmount = floatval($amountString);

        if (!is_float($this->floatAmount)) {
            $this->addError('amount', 'Введите корректное значение');
        }
    }
}
