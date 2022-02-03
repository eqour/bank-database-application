<?php

use app\services\CustomerService;
use app\widgets\PaginationWidget;

$this->registerJsFile('info');

?>

<h1>Информация о клиенте</h1>

<div class="mb-3">
    <button class="btn btn-secondary mb-3">Открыть банковский продукт</button>
</div>
<div class="row mb-3">
    <div class="col mb-3">
        <table class="table table-striped table-bordered">
            <caption>Клиент</caption>
            <tbody>
                <tr>
                    <td>ФИО</td>
                    <td><?= htmlspecialchars($customer->name) ?></td>
                </tr>
                <tr>
                    <td>Паспорт</td>
                    <td><?= htmlspecialchars($customer->passport) ?></td>
                </tr>
                <tr>
                    <td>Дата рождения</td>
                    <td><?= htmlspecialchars($customer->birth_date->format('d.m.Y')) ?></td>
                </tr>
                <tr>
                    <td>Адрес проживания</td>
                    <td><?= htmlspecialchars($customer->residence_address) ?></td>
                </tr>
                <tr>
                    <td>Телефон</td>
                    <td><?= htmlspecialchars($customer->phone_number) ?></td>
                </tr>
                <tr>
                    <td>Пол</td>
                    <td><?= htmlspecialchars((new CustomerService())->gender($customer)) ?></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="col mb-3">
        <form class="form">
            <div class="row">
                <div class="col-6 mb-3">
                    <label class="form-label">Номер счёта</label>
                    <input class="form-control" type="text" name="account-number" maxlength="20" autofocus>
                </div>
                <div class="col-6 mb-3">
                    <label class="form-label">Статус</label>
                    <select class="form-select" name="status">
                        <option value="1" selected>Все</option>
                        <option value="2">Открытые</option>
                        <option value="3">Закрытые</option>
                    </select>
                </div>
                <div class="col-6 mb-3">
                    <label class="form-label">Дата открытия: от</label>
                    <input class="form-control" type="date">
                </div>
                <div class="col-6 mb-3">
                    <label class="form-label">Дата открытия: до</label>
                    <input class="form-control" type="date">
                </div>
                <div class="col-12 mb-3">
                    <button class="btn btn-secondary">Найти</button>
                </div>
            </div>
        </form>
    </div>
</div>
<?php if (count($services) === 0): ?>
    <p>Банковские продукты клиента отсутствуют</p>
<?php else: ?>
    <div class="mb-3">
        <table class="table table-striped table-bordered">
            <caption>Банковские продукты клиента</caption>
            <thead>
                <tr>
                    <th>Название</th>
                    <th>Номер счёта</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($services as $service) { ?>
                    <tr>
                        <td><?= htmlentities($service->service_type->name) ?></td>
                        <td><a href="/banking-product/info?id=<?= htmlentities($service->account_number) ?>"><?= htmlentities($service->account_number) ?></a></td>
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
            $appendParams)
        )->render();
    ?>
<?php endif; ?>
