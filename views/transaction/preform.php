<?php

use app\helpers\FormHelper;

?>

<h1>Выполнение операции по счёту банковского продукта</h1>

<form class="form" method="POST">
    <div class="mb-3">
        <label class="form-label">Счёт банковского продукта</label>
        <input class="form-control" value="<?= $form->getField('accountNumber') ?>" name="<?= FormHelper::fieldName($form, 'accountNumber') ?>" type="text" readonly>
        <?= FormHelper::invalidFeedback($form, 'accountNumber') ?>
    </div>
    <div class="mb-3">
        <label class="form-label">Сумма</label>
        <input class="form-control <?= FormHelper::invalidClass($form, 'amount') ?>" value="<?= $form->getField('amount') ?>" name="<?= FormHelper::fieldName($form, 'amount') ?>" type="text" maxlength="16" autofocus>
        <?= FormHelper::invalidFeedback($form, 'amount') ?>
    </div>
    <div class="mb-3">
        <label class="form-label">Описание операции</label>
        <textarea class="form-control <?= FormHelper::invalidClass($form, 'description') ?>" name="<?= FormHelper::fieldName($form, 'description') ?>" type="text" maxlength="100"><?= $form->getField('description') ?></textarea>
        <?= FormHelper::invalidFeedback($form, 'description') ?>
    </div>
    <div class="mb-3">
        <button class="btn btn-secondary">Выполнить</button>
    </div>
    <?php if (isset($operationRejected)): ?>
        <div class="alert alert-danger">Невозможно выполнить операцию</div>
    <?php endif; ?>
</form>
