<?php

use app\helpers\FormHelper;
use app\widgets\PaginationWidget;

?>

<h1>Информация о банковском продукте</h1>

<div class="mb-3">
    <a href="/transaction/preform?account=<?= $product->account_number ?>" class="btn btn-secondary mb-3 <?= (isset($product->actual_close_date) ? 'disabled' : '') ?>">Новая операция</a>
    <?php if (!isset($product->actual_close_date) && $currentAccountAmount === (float)0): ?>
        <form class="form" method="POST">
            <?= FormHelper::generateGetParameters($appendFormParams); ?>
            <input class="form-control" value="<?= htmlspecialchars($product->account_number) ?>" name="<?= FormHelper::fieldName($closingForm, 'accountNumber') ?>" type="text" hidden>
            <button class="btn btn-secondary mb-3">Закрыть банковский продукт</button>
        </form>
        <?php if (isset($serviceAmountIsNotNull)): ?>
            <div class="alert alert-danger">Сумма на счёте банковского продукта должна быть равна нулю</div>
        <?php endif; ?> 
    <?php endif; ?>
</div>
<div class="row mb-3">
    <div class="col mb-3">
        <table class="table table-striped table-bordered">
            <caption>Банковский продукт</caption>
            <tbody>
                <tr>
                    <td>Номер счёта</td>
                    <td><?= htmlspecialchars($product->account_number) ?></td>
                </tr>
                <tr>
                    <td>Тип продукта</td>
                    <td><?= htmlspecialchars($product->service_type->service_type_group->name) ?></td>
                </tr>
                <tr>
                    <td>Дата открытия</td>
                    <td><?= htmlspecialchars($product->open_date->format('d.m.Y')) ?></td>
                </tr>
                <tr>
                    <td>Планируемая дата закрытия</td>
                    <td><?= htmlspecialchars(isset($product->planned_close_date) ? $product->planned_close_date->format('d.m.Y') : '-') ?></td>
                </tr>
                <tr>
                    <td>Фактическая дата закрытия</td>
                    <td><?= htmlspecialchars(isset($product->actual_close_date) ? $product->actual_close_date->format('d.m.Y') : '-') ?></td>
                </tr>
                <tr>
                    <td>Годовая ставка, %</td>
                    <td><?= htmlspecialchars($product->service_type->annual_rate) ?></td>
                </tr>
                <tr>
                    <td>Начальная сумма по счёту</td>
                    <td><?= htmlspecialchars($product->initial_amount) ?></td>
                </tr>
                <tr>
                    <td>Текущая сумма по счёту</td>
                    <td><?= htmlspecialchars($currentAccountAmount) ?></td>
                </tr>
                <tr>
                    <td>Клиент</td>
                    <td><?= htmlspecialchars($customer->name) ?></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="col mb-3">
        <form class="form" method="GET">
            <div class="mb-3">
                <label class="form-label">Дата операции: от</label>
                <input class="form-control <?= FormHelper::invalidClass($form, 'from') ?>" value="<?= $form->getField('from') ?>" name="<?= FormHelper::fieldName($form, 'from') ?>" type="date" autofocus>
                <?= FormHelper::invalidFeedback($form, 'from') ?>
            </div>
            <div class="mb-3">
                <label class="form-label">Дата операции: до</label>
                <input class="form-control <?= FormHelper::invalidClass($form, 'till') ?>" value="<?= $form->getField('till') ?>" name="<?= FormHelper::fieldName($form, 'till') ?>" type="date">
                <?= FormHelper::invalidFeedback($form, 'till') ?>
            </div>
            <?= FormHelper::generateGetParameters($appendFormParams); ?>
            <div class="mb-3">
                <button class="btn btn-secondary">Найти</button>
            </div>
        </form>
    </div>
</div>
<?php if (count($operations) === 0): ?>
    <p>Отсутствуют операции по счёту</p>
<?php else: ?>
    <div class="mb-3">
        <table class="table table-striped table-bordered">
            <caption>Операции по банковскому продукту</caption>
            <thead>
                <tr>
                    <th>Дата проведения</th>
                    <th>Сумма операции</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($operations as $operation) { ?>
                    <tr>
                        <td><a href="/transaction/info?id=<?= $operation->id ?>"><?= htmlentities($operation->date->format('d.m.Y')) ?></a></td>
                        <td><?= htmlentities($operation->amount) ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    <?php 
        (new PaginationWidget(
            $paginationHelper->getAmountPages(),
            $paginationHelper->getCurrentPage(),
            2,
            $appendPaginationParams)
        )->render();
    ?>
<?php endif; ?>
