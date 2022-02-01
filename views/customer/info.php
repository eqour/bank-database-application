<?php

use app\services\CustomerService;

?>

<h1>Информация о клиенте</h1>

<div class="mb-3">
    <button class="btn btn-secondary mb-3">Открыть банковский продукт</button>
</div>
<div class="mb-3">
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
<div class="mb-3">
    <form class="form">
        <div class="mb-3">
            <label class="form-label">Номер счёта</label>
            <input class="form-control" type="text" maxlength="20" autofocus>
        </div>
        <div class="mb-3">
            <label class="form-label">Статус</label>
            <select class="form-select">
                <option value="1" selected>Все</option>
                <option value="2">Открытые</option>
                <option value="3">Закрытые</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Дата открытия: от</label>
            <input class="form-control" type="date">
        </div>
        <div class="mb-3">
            <label class="form-label">Дата открытия: до</label>
            <input class="form-control" type="date">
        </div>
        <div class="mb-3">
            <button class="btn btn-secondary">Найти</button>
        </div>
    </form>
</div>
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
            <tr>
                <td>Вклад №1</td>
                <td>20396855904736225473</td>
            </tr>
            <tr>
                <td>Вклад №2</td>
                <td>47484947563746473638</td>
            </tr>
            <tr>
                <td>Вклад №3</td>
                <td>32534692846703457684</td>
            </tr>
            <tr>
                <td>Вклад №4</td>
                <td>34536584595963423462</td>
            </tr>
            <tr>
                <td>Кредит №1</td>
                <td>59587419584765325288</td>
            </tr>
            <tr>
                <td>Кредит №2</td>
                <td>11454874965852500532</td>
            </tr>
        </tbody>
    </table>
</div>
