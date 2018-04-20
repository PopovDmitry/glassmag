<h2>Ваш заказ</h2>
<form action="" method="POST">
    <div class="form-group pb-5">
        <?php if (isset($cart)): ?>
        <table class="table table-bordered">
            <?php foreach ($cart as $key => $value):?>
            <tr class="">
                <td><?php echo $key + 1; ?></td>
                <td><?php echo $value; ?></td>
                <td><button type="submit" name="btnDel[<?php echo $key; ?>]" class="btn btn-primary btn-block btn-lg">X</button></td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php else: ?>
            <div>
                <p>Вы ничего не заказали.</p>
            </div>
        <?php endif; ?>
        <div class="fixed-bottom btn-toolbar pb-2 justify-content-center">
            <div class="btn-group col-4 col-sm-4 col-md-4 col-lg-3 col-xl-2 px-1">
                <a href="/index" class="btn btn-primary btn-block btn-lg border-dark">Добавить</a>
            </div>
            <div class="btn-group col-4 col-sm-4 col-md-4 col-lg-3 col-xl-2 px-0">
                <a href="/cart" class="btn btn-primary btn-block disabled btn-lg border-dark">Корзина <?php echo $cartCount; ?></a>
            </div>
            <div class="btn-group col-4 col-sm-4 col-md-4 col-lg-3 col-xl-2 px-1">
                <a href="/order" class="btn btn-primary btn-block btn-lg border-dark">Оправить</a>
            </div>
        </div>
    </div>
</form>