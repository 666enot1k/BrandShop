<?php
$userModel = new \models\Users();
$user = $userModel->getCurrentUser();
?>
<?php foreach ($lastOrder as $order) : ?><!doctype html>

<link rel="shortcut icon" href="image/head.png" type="image/x-icon">
<link href='https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css' rel='stylesheet'>
<body oncontextmenu='return false' class='snippet-body'>
    <link href="/styleOrder.css" type="text/css" rel="stylesheet"/>
<div class="container d-flex justify-content-center">
    <ul class="list-group mt-5 text-white">
        <li class="list-group-item d-flex justify-content-between align-content-center">
            <div class="d-flex flex-row"> <img src="/image/premium.png" width="45" />
                <div class="ml-2">
                    <h4 class="mb-0"><?=$order['name_tov']  ?></h4>
                    <div class="about"><span>Розмір: <?=$order['size'] ?></span><span>Ціна: <?=$order['price'] ?></span> </div>
                </div>
            </div>
        </li>
    </ul>
</div>
<?php endforeach; ?>
    <script type='text/javascript' src='https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js'></script>
    <script type='text/javascript' src=''></script>
    <script type='text/javascript' src=''></script>
    <script type='text/Javascript'></script>
</body>

