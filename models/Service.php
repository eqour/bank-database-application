<?php

namespace app\models;

use DateTime;

class Service {
    public string $account_number;
    public DateTime $open_date;
    public ?DateTime $actual_close_date;
    public ?DateTime $planned_close_date;
    public float $initial_amount;
    public ?string $purpose;
    public ServiceType $service_type;
}
