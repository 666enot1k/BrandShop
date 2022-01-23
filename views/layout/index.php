<?php
$userModel = new \models\Users();
$user = $userModel->getCurrentUser()
?>

<!DOCTYPE html>
<html>
<head>
    <title><?= $MainTitle ?></title>
    <link href="/style.css" type="text/css" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.umd.js"></script>
    <link
            rel="stylesheet"
            href="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.css"
    />
    <link rel="shortcut icon" href="image/head.png" type="image/x-icon">
</head>
<body>
<nav class="navMenu">
    <span><a class="navBar" href="/">BrandShop</a></span>
    <span> <a class="navBar" href="/product">Catalog</a></span>
    <? if (!$userModel->IsUserAuthentication()) : ?>
        <span><a class="navBar" href="/users/register">Register</a>  </span>
        <span> <a class="navBar" href="/users/login">Login</a>  </span>
    <? else: ?>
    <span><a class="navBar" href="/product/basket">Order</a>  </span>
        <span>   <a class="navBar" href="/users/logout">Logout</a>  </span>
    <? endif; ?>
        <? if ($user['login'] == 'admin@admin.com' && $user['password'] == '21232f297a57a5a743894a0e4a801fc3') : ?>
            <span>   <a class="navBar" href="/product/add">Add product</a>  </span>
        <? endif; ?>

</nav>
<div class="container">
    <h1 class="mt-5"><?= $PageTitle ?></h1>
    <? if (!empty($MessageText)) : ?>
        <div class="alert alert-<?= $MessageClass ?>" role="alert">
            <?= $MessageText ?>
        </div>
    <? endif; ?>
    <? ?>
    <?= $PageContent ?>
</div>
<? if ($userModel->IsUserAuthentication()) : ?>
    <script src="/alien/build/ckeditor.js"></script>
    <script>
        let editors = document.querySelectorAll('.editor');
        for (let i in editors) {
            ClassicEditor
                .create(editors[i], {

                    licenseKey: '',


                })
                .then(editor => {
                    window.editor = editor;


                })
                .catch(error => {
                    console.error('Oops, something went wrong!');
                    console.error('Please, report the following error on https://github.com/ckeditor/ckeditor5/issues with the build id and the error stack trace:');
                    console.warn('Build id: 55z9j0iefk7j-nohdljl880ze');
                    console.error(error);
                });
        }
    </script>
<? endif; ?>
</body>
</html>
