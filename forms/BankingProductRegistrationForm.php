<?php

namespace app\forms;

use app\helpers\DateHelper;
use DateTime;

class BankingProductRegistrationForm extends Form {
    public $customer;
    public $type;
    public $plannedCloseDate;
    public $initialAmount;
    public $purpose;

    public ?DateTime $plannedCDate;
    public float $initialFloatAmount;

    protected function fieldNames(): array {
        return [
            'customer',
            'type',
            'plannedCloseDate',
            'initialAmount',
            'purpose'
        ];
    }

    protected function validateFields(): void {
        if (isset($this->plannedCloseDate) && trim($this->plannedCloseDate) !== '') {
            if (DateHelper::trySetDate($this->plannedCloseDate, $this->plannedCDate)) {
                if ($this->plannedCDate <= new DateTime()) {
                    $this->AddError('plannedCloseDate', 'Планируемая дата закрытия должна быть позднее текущего дня');
                }
            } else {
                $this->AddError('plannedCloseDate', 'Укажите корректную дату');
            }
        } else {
            $this->plannedCDate = null;
        }

        if (!isset($this->initialAmount)) {
            $this->addError('initialAmount', 'Заполните поле');
        }
        
        if (1 !== preg_match('/^[0-9,.-]{1,16}$/', $this->initialAmount)) {
            $this->addError('initialAmount', 'Введите корректное значение');
        }

        $amountString = $this->initialAmount;
        $amountString = str_replace(',', '.', $amountString);
        $floatParts = explode('.', $amountString);

        if (isset($floatParts[1]) && mb_strlen($floatParts[1]) > 2) {
            $this->addError('initialAmount', 'Не более двух заков после запятой');
        }

        $this->initialFloatAmount = floatval($amountString);

        if (!is_float($this->initialFloatAmount)) {
            $this->addError('initialAmount', 'Введите корректное значение');
        }

        if (isset($this->purpose)) {
            $this->purpose = trim($this->purpose);
            if ($this->purpose === '') {
                $this->purpose = null;
            } else if (1 !== preg_match('/^[A-Za-zА-Яа-яЁё0-9 -]{1,100}$/u', $this->purpose)) {
                $this->addError('purpose', 'Используйте символы русского и английского алфавита, цифры, а также дефис');
            }
        }
    }
}
