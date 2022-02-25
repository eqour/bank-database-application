<?php

namespace app\services;

use app\application\Application;
use PDO;

class CapitalizationService {
    public static function executeCapitalization(): void {
        $stm = Application::$pdo->prepare('SELECT
            `service`.`account_number` AS `account`,
            (`service`.`initial_amount` + IF(SUM(`operation`.`amount`) IS NULL, 0, SUM(`operation`.`amount`))) AS `sum`,
            `service_type`.`annual_rate` AS `annual_rate`
            FROM (`service` INNER JOIN `service_type` ON `service`.`service_type_id` = `service_type`.`id`) LEFT JOIN `operation` ON `service`.`account_number` = `operation`.`service_account_number`
            WHERE `service`.`actual_close_date` IS NULL
            GROUP BY `service`.`account_number`;');
        $stm->execute();
        $serviceDatas = $stm->fetchAll(PDO::FETCH_ASSOC);

        $currentDate = date('Y:m:d');
        $stm = Application::$pdo->prepare('INSERT INTO `operation` (`date`, `amount`, `service_account_number`, `description`)
            VALUES (:date, :amount, :account, :description);');

        foreach ($serviceDatas as $serviceData) {
            $accountAmount = (float)$serviceData['sum'];
            $percent = ((float)$serviceData['annual_rate'] / 100.0) / 12.0;
            $capitalization = round($accountAmount * $percent, 2);
            $stm->execute([
                'date' => $currentDate,
                'amount' => $capitalization,
                'account' => $serviceData['account'],
                'description' => 'Капитализация'
            ]);
        }
    }
}
