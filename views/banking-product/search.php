<?php

use app\helpers\FormHelper;

?>

<h1>Поиск банковского продукта</h1>

<form class="form" method="POST">
    <div class="mb-3">
        <label class="form-label">Номер счёта</label>
        <input class="form-control <?= FormHelper::invalidClass($form, 'accountNumber') ?>" value="<?= $form->getField('accountNumber') ?>" name="<?= FormHelper::fieldName($form, 'accountNumber') ?>" type="text" maxlength="20" autofocus>
        <?= FormHelper::invalidFeedback($form, 'accountNumber') ?>
    </div>
    <div class="mb-3">
        <button class="btn btn-secondary">Найти</button>
    </div>
    <?php if (isset($doesNotExist)): ?>
        <div class="alert alert-danger">Банковский продукт не найден</div>
    <?php endif; ?>
</form>
