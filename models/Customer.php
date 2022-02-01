<?php

namespace app\models;

use DateTime;

class Customer {
    public const GENDER_FEMALE = 0;
    public const GENDER_MALE = 1;

    public int $id;
    public string $name;
    public DateTime $birth_date;
    public string $passport;
    public string $residence_address;
    public string $phone_number;
    public int $gender;
}
