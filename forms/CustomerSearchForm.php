<?php

namespace app\forms;

class CustomerSearchForm extends Form {
    public string $passport;

    protected function fieldNames(): array {
        return array_merge(parent::fieldNames(), [
            'passport'
        ]);
    }

    protected function validateFields(): void {
        if (!isset($this->passport) || trim($this->passport) === '') {
            $this->addError('passport', 'Заполните поле');
        } else if (1 !== preg_match('/^[0-9]{10}$/', $this->passport)) {
            $this->addError('passport', 'Используйте только цифры. Длина номера: 10 символов.');
        }
    }
}
