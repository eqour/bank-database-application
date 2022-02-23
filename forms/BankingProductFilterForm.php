<?php

namespace app\forms;

use app\helpers\DateHelper;
use DateTime;

class BankingProductFilterForm extends Form {
    public const STATUS_ALL = 0;
    public const STATUS_OPEN = 1;
    public const STATUS_CLOSED = 2;

    public $accountNumber;
    public $status;
    public $from;
    public $till;

    public ?DateTime $dateFrom;
    public ?DateTime $dateTill;

    protected function fieldNames(): array {
        return array_merge(parent::fieldNames(), [
            'accountNumber',
            'status',
            'from',
            'till'
        ]);
    }

    protected function validateFields(): void {
        if (!isset($this->accountNumber) || trim($this->accountNumber) === '') {

            if (isset($this->from) && trim($this->from) === '') {
                $this->from = null;
                $this->dateFrom = null;
            }

            if (isset($this->till) && trim($this->till) === '') {
                $this->till = null;
                $this->dateTill = null;
            }

            if (isset($this->from) && !DateHelper::trySetDate($this->from, $this->dateFrom)) {
                $this->addError('from', 'Используйте корректный формат даты.');
            }

            if (isset($this->till) && !DateHelper::trySetDate($this->till, $this->dateTill)) {
                $this->addError('till', 'Используйте корректный формат даты.');
            }

            if (isset($this->dateFrom) && isset($this->dateTill) && $this->dateFrom > $this->dateTill) {
                $this->addError('from', 'Дата от не может быть больше даты до.');
                $this->addError('till', 'Дата до не может быть меньше даты от.');
            }

            if (!isset($this->status)) {
                $this->addError('status', 'Заполните поле');
            }

            if (isset($this->status) && (((int)$this->status) < 0 || ((int)$this->status) > 2)) {
                $this->addError('status', 'Выберите корректный вариант');
            }

            $this->accountNumber = null;

        } else if (1 === preg_match('/^[0-9]{20}$/', $this->accountNumber)) {
            $this->status = self::STATUS_ALL;
            $this->dateFrom = null;
            $this->dateTill = null;
        } else {
            $this->addError('accountNumber', 'Используйте только цифры. Длина номера: 20 символов.');
        }
    }
}
