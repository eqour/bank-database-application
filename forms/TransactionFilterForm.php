<?php

namespace app\forms;

use app\helpers\DateHelper;
use DateTime;

class TransactionFilterFrom extends Form {
    public $from;
    public $till;

    public ?DateTime $dateFrom;
    public ?DateTime $dateTill;

    protected function fieldNames(): array {
        return array_merge(parent::fieldNames(), [
            'from',
            'till'
        ]);
    }

    protected function validateFields(): void {
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
    }
}
