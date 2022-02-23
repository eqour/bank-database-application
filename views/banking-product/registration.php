<?php

use app\helpers\FormHelper;

$this->registerJsFile('registration');

var_dump($form);

?>

<h1>Оформление банковского продукта</h1>

<div id="root" hidden>
    <form class="form" method="POST">
        <div class="mb-3">
            <label class="form-label">Клиент</label>
            <input class="form-control" type="text" value="<?= $customer->name ?>" disabled>
            <input class="form-control" type="text" value="<?= $customer->id ?>" name="<?= FormHelper::fieldName($form, 'customer') ?>" hidden>
        </div>
        <div class="mb-3">
            <label class="form-label">Тип банковского продукта</label>
            <select class="form-select" name="<?= FormHelper::fieldName($form, 'type') ?>" autofocus>
                <?php for ($i = 0; $i < count($types); $i++) { ?>
                    <option value="<?= htmlspecialchars($types[$i]->id) ?>" data-product="<?= ($types[$i]->service_type_group->name === 'вклад' ? 'deposit' : 'credit') ?>" <?= ((!isset($form->type) && $i === 0) || (isset($form->type) && $form->type == $types[$i]->id) ? 'selected' : '') ?>><?= htmlspecialchars($types[$i]->name) ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Планируемая дата закрытия</label>
            <input class="form-control <?= FormHelper::invalidClass($form, 'plannedCloseDate') ?>" value="<?= $form->getField('plannedCloseDate') ?>" name="<?= FormHelper::fieldName($form, 'plannedCloseDate') ?>" type="date">
            <?= FormHelper::invalidFeedback($form, 'plannedCloseDate') ?>
        </div>
        <div class="mb-3">
            <label class="form-label">Начальная сумма</label>
            <input class="form-control <?= FormHelper::invalidClass($form, 'initialAmount') ?>" value="<?= $form->getField('initialAmount') ?>" name="<?= FormHelper::fieldName($form, 'initialAmount') ?>" type="text" maxlength="16">
            <?= FormHelper::invalidFeedback($form, 'initialAmount') ?>
        </div>
        <div class="mb-3">
            <label class="form-label">Причина открытия</label>
            <textarea class="form-control <?= FormHelper::invalidClass($form, 'purpose') ?>" name="<?= FormHelper::fieldName($form, 'purpose') ?>" type="text" maxlength="100"><?= $form->getField('purpose') ?></textarea>
            <?= FormHelper::invalidFeedback($form, 'purpose') ?>
        </div>
        <div class="mb-3">
            <button class="btn btn-secondary">Оформить</button>
        </div>
        <?= FormHelper::csrf($form) ?>
    </form>
</div>
