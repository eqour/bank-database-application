<?php $this->registerJsFile('registration'); ?>

<h1>Оформление банковского продукта</h1>

<div id="root" hidden>
    <form class="form">
        <div class="mb-3">
            <label class="form-label">Клиент</label>
            <input class="form-control" type="text" value="Иванов Иван Иванович" disabled>
        </div>
        <div class="mb-3">
            <label class="form-label">Тип банковского продукта</label>
            <select class="form-select" name="product-type" autofocus>
                <option value="1" data-product="deposit" selected>Вклад №1</option>
                <option value="2" data-product="deposit">Вклад №2</option>
                <option value="3" data-product="deposit">Вклад №3</option>
                <option value="4" data-product="credit">Займ №1</option>
                <option value="5" data-product="credit">Займ №2</option>
                <option value="6" data-product="credit">Займ №3</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Планируемая дата закрытия</label>
            <input class="form-control" type="date">
        </div>
        <div class="mb-3">
            <label class="form-label">Начальная сумма</label>
            <input class="form-control" type="text" maxlength="16">
        </div>
        <div class="mb-3">
            <label class="form-label">Причина открытия</label>
            <textarea class="form-control" type="text" name="open-purpose" maxlength="100"></textarea>
        </div>
        <div class="mb-3">
            <button class="btn btn-secondary">Оформить</button>
        </div>
    </form>
</div>
