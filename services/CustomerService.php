<?php

namespace app\services;

use app\application\Application;
use app\models\Customer;
use DateTime;
use Exception;

class CustomerService {
    public function createCustomer(int $id,
            string $name,
            DateTime $birth_date,
            string $passport,
            string $residence_address,
            string $phone_number,
            int $gender): Customer {
        $customer = new Customer();
        $customer->id = $id;
        $customer->name = $name;
        $customer->birth_date = $birth_date;
        $customer->passport = $passport;
        $customer->residence_address = $residence_address;
        $customer->phone_number = $phone_number;
        $customer->gender = $gender;
        return $customer;
    }

    private function createCustomerFromFetchResult(array $result): Customer {
        return $this->createCustomer(
            $result['id'],
            $result['name'],
            new DateTime($result['birth_date']),
            $result['passport'],
            $result['residence_address'],
            $result['phone_number'],
            $result['gender']
        );
    }

    public function findByPassport(string $passport): ?Customer {
        $stm = Application::$pdo->prepare('SELECT * FROM `customer` INNER JOIN `contract` ON `customer`.`id` = `contract`.`customer_id` WHERE `passport` = :passport;');
        $stm->bindValue('passport', $passport);
        $stm->execute();
        $customer = $stm->fetch();
        return $customer === false ? null : $this->createCustomerFromFetchResult($customer);
    }

    public function findByBankingProductAccountNumber(string $accountNumber): ?Customer {
        $stm = Application::$pdo->prepare('SELECT *
            FROM `customer` INNER JOIN (`contract` INNER JOIN `service` ON `service`.`contract_id` = `contract`.`id`) ON `contract`.`customer_id` = `customer`.`id`
            WHERE `service`.`account_number` = :accountnumber;');
        $stm->bindValue('accountnumber', $accountNumber);
        $stm->execute();
        $customer = $stm->fetch();
        return $customer === false ? null : $this->createCustomerFromFetchResult($customer);
    }

    public function findById(string $id): ?Customer {
        $stm = Application::$pdo->prepare('SELECT * FROM `customer` INNER JOIN `contract` ON `customer`.`id` = `contract`.`customer_id` WHERE `customer`.`id` = :id;');
        $stm->bindValue('id', $id);
        $stm->execute();
        $customer = $stm->fetch();
        return $customer === false ? null : $this->createCustomerFromFetchResult($customer);
    }

    public function gender(Customer $customer): string {
        switch ($customer->gender) {
            case Customer::GENDER_MALE: return 'Мужской';
            case Customer::GENDER_FEMALE: return 'Женский';
            default: throw new Exception();
        }
    }
}
