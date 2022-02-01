<h2>Пример выполнения запроса: вывод всех имён клиентов банка</h2>
<?php
    foreach ($customers as $customer) {
        echo '<p>' . $customer['name'] . '</p>';
    }
?>
