<?php

namespace app\services;

use app\application\Application;
use app\helpers\PaginationHelper;
use app\models\ServiceType;
use app\models\ServiceTypeGroup;
use PDO;

class ServiceTypeService {
    public function createServiceType(int $id,
            string $name,
            ?string $description,
            float $annual_rate,
            bool $replenishment,
            bool $withdrawal,
            ServiceTypeGroup $service_type_group): ServiceType {
        $serviceType = new ServiceType();
        $serviceType->id = $id;
        $serviceType->name = $name;
        $serviceType->description = $description;
        $serviceType->annual_rate = $annual_rate;
        $serviceType->replenishment = $replenishment;
        $serviceType->withdrawal = $withdrawal;
        $serviceType->service_type_group = $service_type_group;
        return $serviceType;
    }

    public function createServiceTypes(array $fetchResult): array {
        $serviceTypes = [];
        foreach ($fetchResult as $row) {
            $serviceTypes[] = $this->createServiceType(
                $row['type_id'],
                $row['type_name'],
                $row['description'],
                $row['annual_rate'],
                $row['replenishment'],
                $row['withdrawal'],
                (new ServiceTypeGroupService())->createServiceTypeGroup($row['group_id'], $row['group_name'])
            );
        }
        return $serviceTypes;
    }
    
    public function findAll(): array {
        $stm = Application::$pdo->prepare('SELECT
            `service_type`.`id` AS `type_id`,
            `service_type`.`name` AS `type_name`,
            `service_type_group`.`id` AS `group_id`,
            `service_type_group`.`name` AS `group_name`,
            `description`, `annual_rate`,
            `replenishment`,
            `withdrawal`
            FROM `service_type` INNER JOIN `service_type_group` ON `service_type`.`service_type_group_id` = `service_type_group`.`id`;');
        $stm->execute();
        return $this->createServiceTypes($stm->fetchAll());
    }

    public function findAllInRange(int $min, int $max): array {
        $stm = Application::$pdo->prepare('SELECT
            `service_type`.`id` AS `type_id`,
            `service_type`.`name` AS `type_name`,
            `service_type_group`.`id` AS `group_id`,
            `service_type_group`.`name` AS `group_name`,
            `description`, `annual_rate`,
            `replenishment`,
            `withdrawal`
            FROM `service_type` INNER JOIN `service_type_group` ON `service_type`.`service_type_group_id` = `service_type_group`.`id`
            LIMIT :offset, :amount;');
        $stm->bindValue('offset', $min - 1, PDO::PARAM_INT);
        $stm->bindValue('amount', $max - ($min - 1), PDO::PARAM_INT);
        $stm->execute();
        return $this->createServiceTypes($stm->fetchAll());
    }

    public function getPaginationHelper(int $currentPage): PaginationHelper {
        return new PaginationHelper($this->getRecordsCount(), $currentPage, Application::$maxRecordsPerPage);
    }

    public function getRecordsCount(): int {
        $stm = Application::$pdo->prepare('SELECT COUNT(*) AS "count" FROM `service_type`;');
        $stm->execute();
        $fetchResult = $stm->fetch();
        return $fetchResult['count'];
    }
}
