<h1>Информация об операции по счёту банковского продукта</h1>

<?php if (!isset($operation)): ?>
    <p>Операция не найдена</p>
<?php else: ?>
    <div class="mb-3">
        <table class="table table-striped table-bordered table-sm">
            <caption>Операция по счёту банковского продукта</caption>
            <tbody>
                <tr>
                    <td>Дата проведения</td>
                    <td><?= htmlspecialchars($operation->date->format('d.m.Y')) ?></td>
                </tr>
                <tr>
                    <td>Сумма операции</td>
                    <td><?= htmlspecialchars($operation->amount) ?></td>
                </tr>
                <tr>
                    <td>Описание операции</td>
                    <td><?= htmlspecialchars(isset($operation->description) ? $operation->description : '-') ?></td>
                </tr>
                <tr>
                    <td>Счёт банковского продукта</td>
                    <td><a href="/banking-product/info?account=<?= htmlspecialchars($operation->service->account_number) ?>"><?= htmlspecialchars($operation->service->account_number) ?></a></td>
                </tr>
            </tbody>
        </table>
    </div>
<?php endif; ?>
