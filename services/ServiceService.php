<?php

namespace app\services;

use app\application\Application;
use app\forms\BankingProductFilterForm;
use app\helpers\PaginationHelper;
use app\models\Service;
use app\models\ServiceType;
use DateTime;
use PDO;

class ServiceService {
    function createService(string $account_number,
    DateTime $open_date,
    ?DateTime $actual_close_date,
    ?DateTime $planned_close_date,
    float $initial_amount,
    ?string $purpose,
    ServiceType $service_type): Service {
        $service = new Service();
        $service->account_number = $account_number;
        $service->open_date = $open_date;
        $service->actual_close_date = $actual_close_date;
        $service->planned_close_date = $planned_close_date;
        $service->initial_amount = $initial_amount;
        $service->purpose = $purpose;
        $service->service_type = $service_type;
        return $service;
    }

    public function createServiceFromFetchResult(array $result): Service {
        return $this->createService(
            $result['account_number'],
            isset($result['open_date']) ? new DateTime($result['open_date']) : null,
            isset($result['actual_close_date']) ? new DateTime($result['actual_close_date']) : null,
            isset($result['planned_close_date']) ? new DateTime($result['planned_close_date']) : null,
            $result['initial_amount'],
            $result['purpose'],
            (new ServiceTypeService())->createServiceType(
                $result['service_type_id'],
                $result['service_type_name'],
                $result['service_type_description'],
                $result['service_type_annual_rate'],
                $result['service_type_replensihment'],
                $result['service_type_withdrawal'],
                (new ServiceTypeGroupService())->createServiceTypeGroup(
                    $result['type_group_id'],
                    $result['type_group_name']
                )
            )
        );
    }

    public function findByAccountNumber(string $accountNumber): ?Service {
        $stm = Application::$pdo->prepare('SELECT
            `service`.`account_number`,
            `service`.`open_date`,
            `service`.`actual_close_date`,
            `service`.`planned_close_date`,
            `service`.`initial_amount`,
            `service`.`purpose`,
            `service_type`.`id` AS `service_type_id`,
            `service_type`.`name` AS `service_type_name`,
            `service_type`.`description` AS `service_type_description`,
            `service_type`.`annual_rate` AS `service_type_annual_rate`,
            `service_type`.`replenishment` AS `service_type_replensihment`,
            `service_type`.`withdrawal` AS `service_type_withdrawal`,
            `service_type_group`.`id` AS `type_group_id`,
            `service_type_group`.`name` AS `type_group_name`
            FROM `service` INNER JOIN (`service_type` INNER JOIN `service_type_group` ON `service_type`.`service_type_group_id` = `service_type_group`.`id`) ON `service`.`service_type_id` = `service_type`.`id`
            WHERE `account_number` = :accountnumber;');
        $stm->bindValue('accountnumber', $accountNumber);
        $stm->execute();
        $service = $stm->fetch();
        return $service === false ? null : $this->createServiceFromFetchResult($service);
    }

    public function findAllByCustomerIdForCustomer(string $customerId): array {
        $stm = Application::$pdo->prepare('SELECT
            `service`.`account_number` AS `account_number`,
            `service_type`.`name` AS `name`
            FROM (`service` INNER JOIN (`contract` INNER JOIN `customer` ON `contract`.`customer_id` = `customer`.`id`) ON `service`.`contract_id` = `contract`.`id`) INNER JOIN  `service_type` ON `service`.`service_type_id` = `service_type`.`id`
            WHERE `customer`.`id` = :customerid;');
        $stm->bindValue('customerid', $customerId, PDO::PARAM_INT);
        $stm->execute();
        $result = $stm->fetchAll();
        
        $services = [];
        foreach ($result as $serviceFetchResult) {
            $service = new Service();
            $service->account_number = $serviceFetchResult['account_number'];
            $service->service_type = new ServiceType();
            $service->service_type->name = $serviceFetchResult['name'];
            $services[] = $service;
        }
        return $services;
    }
    
    public function findAllByCustomerIdForCustomerAndFilter(string $customerId, ?DateTime $from, ?DateTime $till, ?int $status, ?string $accountNumber, int $min, int $max): array {
        $stm = Application::$pdo->prepare('SELECT
            `service`.`account_number` AS `account_number`,
            `service`.`open_date`,
            `service_type`.`name` AS `name`
            FROM (`service` INNER JOIN (`contract` INNER JOIN `customer` ON `contract`.`customer_id` = `customer`.`id`) ON `service`.`contract_id` = `contract`.`id`) INNER JOIN `service_type` ON `service`.`service_type_id` = `service_type`.`id`
            WHERE
            `customer`.`id` = :customerid
            '.(isset($accountNumber) ? ('AND `service`.`account_number` = "'.$accountNumber.'" ') : '').'
            '.(!isset($accountNumber) && isset($from) ? ('AND `service`.`open_date` >= "'.$from->format('Y-m-d').'" ') : '').'
            '.(!isset($accountNumber) && isset($till) ? ('AND `service`.`open_date` <= "'.$till->format('Y-m-d').'" ') : '').'
            '.(!isset($accountNumber) && isset($status) && $status !== BankingProductFilterForm::STATUS_ALL ? ('AND `service`.`actual_close_date` IS '.($status === BankingProductFilterForm::STATUS_OPEN ? 'NULL' : 'NOT NULL')) : '').'
            ORDER BY `service`.`open_date` DESC, `service`.`account_number` DESC
            LIMIT :offset, :amount;');
        $stm->bindValue('customerid', $customerId, PDO::PARAM_INT);
        $stm->bindValue('offset', $min - 1, PDO::PARAM_INT);
        $stm->bindValue('amount', $max - ($min - 1), PDO::PARAM_INT);
        $stm->execute();
        $result = $stm->fetchAll();
        
        $services = [];
        foreach ($result as $serviceFetchResult) {
            $service = new Service();
            $service->account_number = $serviceFetchResult['account_number'];
            $service->service_type = new ServiceType();
            $service->service_type->name = $serviceFetchResult['name'];
            $services[] = $service;
        }
        return $services;
    }

    public function getPaginationHelper(int $currentPage, string $customerId): PaginationHelper {
        return new PaginationHelper($this->getRecordsCountByCustomerId($customerId), $currentPage, Application::$maxRecordsPerPage);
    }

    public function getRecordsCountByCustomerId(string $customerId): int {
        $stm = Application::$pdo->prepare('SELECT COUNT(*) AS `count`
            FROM (`service` INNER JOIN (`contract` INNER JOIN `customer` ON `contract`.`customer_id` = `customer`.`id`) ON `service`.`contract_id` = `contract`.`id`) INNER JOIN  `service_type` ON `service`.`service_type_id` = `service_type`.`id`
            WHERE `customer`.`id` = :customerid;');
        $stm->bindValue('customerid', $customerId, PDO::PARAM_INT);
        $stm->execute();
        $fetchResult = $stm->fetch();
        return $fetchResult['count'];
    }

    public function getRecordsCountByCustomerIdForCustomerAndFilter(string $customerId, ?DateTime $from, ?DateTime $till, ?int $status, ?string $accountNumber): int {
        $stm = Application::$pdo->prepare('SELECT COUNT(*) AS `count`
            FROM (`service` INNER JOIN (`contract` INNER JOIN `customer` ON `contract`.`customer_id` = `customer`.`id`) ON `service`.`contract_id` = `contract`.`id`) INNER JOIN `service_type` ON `service`.`service_type_id` = `service_type`.`id`
            WHERE
            `customer`.`id` = :customerid
            '.(isset($accountNumber) ? ('AND `service`.`account_number` = "'.$accountNumber.'" ') : '').'
            '.(!isset($accountNumber) && isset($from) ? ('AND `service`.`open_date` >= "'.$from->format('Y-m-d').'" ') : '').'
            '.(!isset($accountNumber) && isset($till) ? ('AND `service`.`open_date` <= "'.$till->format('Y-m-d').'" ') : '').'
            '.(!isset($accountNumber) && isset($status) && $status !== BankingProductFilterForm::STATUS_ALL ? ('AND `service`.`actual_close_date` IS '.($status === BankingProductFilterForm::STATUS_OPEN ? 'NULL' : 'NOT NULL')) : '').';');
        $stm->bindValue('customerid', $customerId, PDO::PARAM_INT);
        $stm->execute();
        $fetchResult = $stm->fetch();
        return $fetchResult['count'];
    }

    public function getCurrentAccountAmount(string $accountNumber): float {
        $stm = Application::$pdo->prepare('SELECT (`service`.`initial_amount` + IF(SUM(`operation`.`amount`) IS NULL, 0, SUM(`operation`.`amount`))) AS `sum`
            FROM `service` LEFT JOIN `operation` ON `service`.`account_number` = `operation`.`service_account_number`
            WHERE `service`.`account_number` = :accountnumber;');
        $stm->bindValue('accountnumber', $accountNumber);
        $stm->execute();
        $fetchResult = $stm->fetch();
        return $fetchResult['sum'];
    }

    public function closeService(string $accountNumber): void {
        $stm = Application::$pdo->prepare('UPDATE `service`
            SET `service`.`actual_close_date` = :closedate
            WHERE `service`.`account_number` = :accountnumber
            AND `service`.`actual_close_date` IS NULL;');
        $stm->bindValue('accountnumber', $accountNumber);
        $stm->bindValue('closedate', date('Y-m-d'));
        $stm->execute();
    }

    public function register(string $customerId, string $serviceTypeId, float $initialAmount, ?DateTime $plannedCloseDate = null, ?string $purpose = null): void {
        $stm = Application::$pdo->prepare('INSERT
            INTO `service` (`account_number`, `open_date`, `planned_close_date`, `initial_amount`, `purpose`, `service_type_id`, `contract_id`)
            VALUES (IF((SELECT * FROM (SELECT MAX(LPAD(CAST(`account_number` AS DECIMAL(20, 0)) + 1, 20, \'0\')) FROM `service`) AS `s1`) IS NOT NULL, (SELECT * FROM (SELECT MAX(LPAD(CAST(`account_number` AS DECIMAL(20, 0)) + 1, 20, \'0\')) FROM `service`) AS `s1`), \'00000000000000000001\'),
            :open_date, :close_date, :initial_amount, :purpose, :service_type,
            (SELECT `contract`.`id` FROM `contract` INNER JOIN `customer` ON `customer`.`id` = `contract`.`customer_id` WHERE `customer`.`id` = :customer_id AND (`contract`.`determination_date` IS NULL OR `contract`.`determination_date` > NOW()) ORDER BY `contract`.`conclusion_date` LIMIT 0, 1));');
        $stm->bindValue('open_date', date('Y-m-d'));
        $stm->bindValue('close_date', (isset($plannedCloseDate) ? $plannedCloseDate->format('Y-m-d') : null));
        $stm->bindValue('initial_amount', $initialAmount);
        $stm->bindValue('purpose', $purpose);
        $stm->bindValue('service_type', $serviceTypeId, PDO::PARAM_INT);
        $stm->bindValue('customer_id', $customerId, PDO::PARAM_INT);
        $stm->execute();
    }
}
