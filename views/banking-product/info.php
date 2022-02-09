<h1>Информация о банковском продукте</h1>

<div class="mb-3">
    <button class="btn btn-secondary mb-3">Новая операция</button>
</div>
<div class="mb-3">
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
                <td>Клиент</td>
                <td><?= htmlspecialchars($customer->name) ?></td>
            </tr>
        </tbody>
    </table>
</div>
<div class="mb-3">
    <form class="form">
        <div class="mb-3">
            <label class="form-label">Дата операции: от</label>
            <input class="form-control" type="date" autofocus>
        </div>
        <div class="mb-3">
            <label class="form-label">Дата операции: до</label>
            <input class="form-control" type="date">
        </div>
        <div class="mb-3">
            <button class="btn btn-secondary">Найти</button>
        </div>
    </form>
</div>
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
            <tr>
                <td>05.01.2022</td>
                <td>10 000</td>
            </tr>
            <tr>
                <td>07.01.2022</td>
                <td>-7 000</td>
            </tr>
            <tr>
                <td>10.01.2022</td>
                <td>13 000</td>
            </tr>
        </tbody>
    </table>
</div>
