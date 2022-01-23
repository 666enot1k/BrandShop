<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title></title>
    <link rel="stylesheet" href="../css/styleEdit.css">
    <link rel="shortcut icon" href="image/head.png" type="image/x-icon">
</head>
<body>
<div class="mainscreen">

    <div class="card">
        <div class="leftside">
            <? if (is_file('files/product/' . $model['photo'] . '_m.jpg')) : ?>
                <img class="product" src="/files/product/<?= $model['photo'] ?>_m.jpg"/>
            <? endif; ?>
        </div>
        <div class="rightside">
            <form method="post" action="" enctype="multipart/form-data">
                <h1>Edit</h1>
                <h2>Product Information</h2>
                <p>Product Name</p>
                <input type="text" class="inputbox" name="name_tov" value="<?= $model['name_tov'] ?>"/>
                <p>Price</p>
                <input type="text" class="inputbox" name="price" value="<?= $model['price'] ?>"/>
                <p>About Product</p>
                <textarea class="inputbox" name="text"> <?= $model['text'] ?></textarea>
                <div class="example-2">
                    <div class="form-group">
                        <input type="file" name="file" id="file" class="input-file" accept="image/jpeg, image/png">
                        <label for="file" class="btn btn-tertiary js-labelFile">
                            <span class="js-fileName">Upload file</span>
                        </label>
                    </div>
                </div>
                <p></p>
                <button type="submit" class="button">Submit</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>
