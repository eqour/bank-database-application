<?php

namespace app\models;

use DateTime;

class Operation {
    public int $id;
    public DateTime $date;
    public float $amount;
    public Service $service;
    public ?string $description;
}
