<?php

use app\helpers\FormHelper;

?>

<h1>Поиск клиента</h1>

<form class="form" method="POST">
    <div class="mb-3">
        <label class="form-label">Серия и номер паспорта</label>
        <input class="form-control <?= FormHelper::invalidClass($form, 'passport') ?>" value="<?= $form->getField('passport') ?>" name="<?= FormHelper::fieldName($form, 'passport') ?>" type="text" maxlength="10" autofocus>
        <?= FormHelper::invalidFeedback($form, 'passport') ?>
    </div>
    <div class="mb-3">
        <button class="btn btn-secondary">Найти</button>
    </div>
    <?php if (isset($doesNotExist)): ?>
        <div class="alert alert-danger">Клиент не найден</div>
    <?php endif; ?>
    <?= FormHelper::csrf($form) ?>
</form>
