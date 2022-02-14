<?php

namespace app\services;

use app\application\Application;
use app\models\Operation;
use app\models\Service;
use DateTime;
use PDO;

class OperationService {
    public function createOperation(int $id,
            DateTime $date,
            float $amount,
            Service $service,
            ?string $description): Operation {
        $operation = new Operation();
        $operation->id = $id;
        $operation->date = $date;
        $operation->amount = $amount;
        $operation->service = $service;
        $operation->description = $description;
        return $operation;
    }

    private function createOperationFromFetchResult(array $result): Operation {
        return $this->createOperation(
            $result['id'],
            new DateTime($result['date']),
            $result['amount'],
            (new ServiceService())->createServiceFromFetchResult($result),
            $result['description']
        );
    }

    public function findById(string $id): ?Operation {
        $stm = Application::$pdo->prepare('SELECT
            `operation`.`id`,
            `operation`.`date`,
            `operation`.`amount`,
            `operation`.`description`,
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
            FROM `operation` INNER JOIN (`service` INNER JOIN (`service_type` INNER JOIN `service_type_group` ON `service_type`.`service_type_group_id` = `service_type_group`.`id`) ON `service`.`service_type_id` = `service_type`.`id`) ON `operation`.`service_account_number` = `service`.`account_number`
            WHERE `operation`.`id` = :id;');
        $stm->bindValue('id', $id);
        $stm->execute();
        $operation = $stm->fetch();
        return $operation === false ? null : $this->createOperationFromFetchResult($operation);
    }

    public function findAllByAccountNumber(string $accountNumber): array {
        $stm = Application::$pdo->prepare('SELECT
            `operation`.`id`,
            `operation`.`date`,
            `operation`.`amount`,
            `operation`.`description`,
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
            FROM `operation` INNER JOIN (`service` INNER JOIN (`service_type` INNER JOIN `service_type_group` ON `service_type`.`service_type_group_id` = `service_type_group`.`id`) ON `service`.`service_type_id` = `service_type`.`id`) ON `operation`.`service_account_number` = `service`.`account_number`
            WHERE `service`.`account_number` = :accountnumber;');
        $stm->bindValue('accountnumber', $accountNumber);
        $stm->execute();
        $result = $stm->fetchAll();
        
        $operations = [];
        foreach ($result as $operationFetchResult) {
            $operations[] = $this->createOperationFromFetchResult($operationFetchResult);
        }
        return $operations;
    }

    public function findAllByAccountNumberAndFilter(string $accountNumber, ?DateTime $from, ?DateTime $till, int $min, int $max): array {
        $stm = Application::$pdo->prepare('SELECT
            `operation`.`id`,
            `operation`.`date`,
            `operation`.`amount`,
            `operation`.`description`,
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
            FROM `operation` INNER JOIN (`service` INNER JOIN (`service_type` INNER JOIN `service_type_group` ON `service_type`.`service_type_group_id` = `service_type_group`.`id`) ON `service`.`service_type_id` = `service_type`.`id`) ON `operation`.`service_account_number` = `service`.`account_number`
            WHERE `service`.`account_number` = :accountnumber
            '.(isset($from) ? ('AND `operation`.`date` >= "'.$from->format('Y-m-d').'" ') : '').'
            '.(isset($till) ? ('AND `operation`.`date` <= "'.$till->format('Y-m-d').'" ') : '').'
            ORDER BY `operation`.`date` DESC
            LIMIT :offset, :amount;');
        $stm->bindValue('accountnumber', $accountNumber);
        $stm->bindValue('offset', $min - 1, PDO::PARAM_INT);
        $stm->bindValue('amount', $max - ($min - 1), PDO::PARAM_INT);
        $stm->execute();
        $result = $stm->fetchAll();
        
        $operations = [];
        foreach ($result as $operationFetchResult) {
            $operations[] = $this->createOperationFromFetchResult($operationFetchResult);
        }
        return $operations;
    }

    public function getRecordsCountByAccountNumberAndFilter(string $accountNumber, ?DateTime $from, ?DateTime $till): int {
        $stm = Application::$pdo->prepare('SELECT COUNT(*) AS `count`
            FROM `operation` INNER JOIN (`service` INNER JOIN (`service_type` INNER JOIN `service_type_group` ON `service_type`.`service_type_group_id` = `service_type_group`.`id`) ON `service`.`service_type_id` = `service_type`.`id`) ON `operation`.`service_account_number` = `service`.`account_number`
            WHERE `service`.`account_number` = :accountnumber
            '.(isset($from) ? ('AND `operation`.`date` >= "'.$from->format('Y-m-d').'" ') : '').'
            '.(isset($till) ? ('AND `operation`.`date` <= "'.$till->format('Y-m-d').'" ') : '').';');
        $stm->bindValue('accountnumber', $accountNumber);
        $stm->execute();
        $fetchResult = $stm->fetch();
        return $fetchResult['count'];
    }

    public function preform(string $accountNumber, float $amount, ?string $description = null): void {
        $stm = Application::$pdo->prepare('INSERT INTO `operation` (`date`, `amount`, `service_account_number`, `description`)
            VALUES (:date, :amount, :accountnumber, :description);');
        
        $stm->bindValue('accountnumber', $accountNumber);
        $stm->bindValue('date', date('Y-m-d H:i:s'));
        $stm->bindValue('amount', $amount);
        $stm->bindValue('description', $description);

        $stm->execute();
    }
}
