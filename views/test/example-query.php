<h2>Пример выполнения запроса: вывод всех имён клиентов банка</h2>
<?php
    foreach ($clients as $client) {
        echo '<p>' . $client['name'] . '</p>';
    }
?>
