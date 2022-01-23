<link rel="stylesheet" href="/css/styleNotify.css">
<link rel="shortcut icon" href="image/head.png" type="image/x-icon">
<div id="container">
    <div id="success-box">
            <div class="check"></div>
        <div class="message"><h1 class="alert">Delete</h1></div>
        <a href="/product/delete?id=<?=$model['id'] ?>&confirm=yes" class="button-box"><h1 class="green">Yes</h1></a>
    </div>
    <div id="error-box">
        <div class="close"></div>
        <div class="message"><h1 class="alert">Do not delete</h1></div>
        <a href="<?=$_SERVER['HTTP_REFERER'] ?>" class="button-box"><h1 class="red">No</h1></a>
    </div>
</div>
