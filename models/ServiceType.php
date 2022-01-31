<?php

namespace app\models;

class ServiceType {
    public int $id;
    public string $name;
    public ?string $description;
    public float $annual_rate;
    public bool $replenishment;
    public bool $withdrawal;
    public ServiceTypeGroup $service_type_group;
}
