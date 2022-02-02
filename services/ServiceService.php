<?php

namespace app\services;

use app\application\Application;
use app\helpers\PaginationHelper;
use app\models\Service;
use app\models\ServiceType;
use PDO;

class ServiceService {
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
}
