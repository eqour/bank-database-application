<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable: no">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/main.css">
    <title>Application</title>
</head>
<body>
    <header class="d-flex align-items-center px-1">
        <img id="logo" src="img/logo.svg">
    </header>
    <div class="main row">
        <div class="col-auto navigation">
            <a href="#" class="navigation-item">
                Поиск клиента
            </a>
            <a href="#" class="navigation-item">
                Поиск банковского продукта
            </a>
            <a href="#" class="navigation-item">
                Информация о банковских продуктах
            </a>
        </div>
        <div class="col-auto">
            <main>
                <div class="container">
                    <?= $content ?>
                </div>
            </main>
        </div>
    </div>
    <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
