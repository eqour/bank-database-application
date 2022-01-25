<?php

namespace app\services;

use app\application\Application;
use app\models\ServiceType;
use app\models\ServiceTypeGroup;

class ServiceTypeService
{
    public function createService(int $id,
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
        $fetchResult = $stm->fetchAll();

        $serviceTypes = [];
        foreach ($fetchResult as $row) {
            $serviceTypes[] = $this->createService(
                $row['type_id'],
                $row['type_name'],
                $row['description'],
                $row['annual_rate'],
                $row['replenishment'],
                $row['withdrawal'],
                (new ServiceTypeGroupService())->createServiceTypeGroup($row['group_id'], $row['group_name']));
        }
        return $serviceTypes;
    }
}
