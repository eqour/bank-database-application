<?php

use app\widgets\PaginationWidget;

?>

<h1>Информация о банковских продуктах</h1>

<?php if (count($products) === 0): ?>
    <p>Банковские продукты отсутствуют</p>
<?php else: ?>
    <table class="table table-striped table-bordered table-sm">
        <caption>Банковские продукты</caption>
        <thead>
            <tr>
                <th>Название</th>
                <th>Годовая ставка, %</th>
                <th>Пополнение</th>
                <th>Снятие</th>
                <th>Описание</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $product) { ?>
                <tr>
                    <td><?= htmlspecialchars($product->name) ?></td>
                    <td class="text-center"><?= htmlspecialchars($product->annual_rate) ?></td>
                    <td class="text-center"><?= ($product->replenishment ? '+' : '-') ?></td>
                    <td class="text-center"><?= ($product->withdrawal ? '+' : '-') ?></td>
                    <td><?= htmlspecialchars($product->description) ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    <?php 
        (new PaginationWidget(
            $paginationHelper->getAmountPages(),
            $paginationHelper->getCurrentPage(),
            2)
        )->render();
    ?>
<?php endif; ?>
