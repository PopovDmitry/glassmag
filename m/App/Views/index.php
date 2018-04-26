<h2>Заказать&nbsp;стеклопакет</h2>
<form action="/m/index" method="POST">
    <div class="form-group pb-5">
        <label for="articul" class="col-form-label">Артикул</label>
        <select name="articul" id="articul" class="form-control<?php echo $errors['articul']; ?>">
            <option value="4М1-16-4М1" <?php echo $values['articul1']; ?>>4М1-16-4М1</option>
            <option value="4М1-16-4И" <?php echo $values['articul2']; ?>>4М1-16-4И</option>
            <option value="4М1-10-4М1-10-4М1" <?php echo $values['articul3']; ?>>4М1-10-4М1-10-4М1</option>
            <option value="4М1-10-4М1-10-4И" <?php echo $values['articul4']; ?>>4М1-10-4М1-10-4И</option>
            <option value="4М1-12-4М1-12-4И" <?php echo $values['articul5']; ?>>4М1-12-4М1-12-4И</option>
            <option value="4М1-12-4М1-12-4М1" <?php echo $values['articul6']; ?>>4М1-12-4М1-12-4М1</option>
            <option value="4М1-14-4М1-14-4М1" <?php echo $values['articul7']; ?>>4М1-14-4М1-14-4М1</option>
            <option value="4М1-14-4М1-14-4И" <?php echo $values['articul8']; ?>>4М1-14-4М1-14-4И</option>
            <!--option value="11" <?php echo $values['articul9']; ?>>Нестандартный артикул</option-->
        </select>
        <!--div id="oarticul-frame" class="d-none">
            <label for="oarticul" class="col-form-label">Нестандартный артикул</label>
            <input type="text" name="oarticul" id="oarticul" class="form-control" value="<?php echo $values['width']; ?>" placeholder="Нестандартный артикул">
        </div-->
        <label for="width"  class="col-form-label">Высота, мм</label>
        <input type="number" name="height" id="height" min="100" class="form-control<?php echo $errors['height']; ?>" value="<?php echo $values['height']; ?>" placeholder="0" required="required">
        <label for="width" class="col-form-label">Ширина, мм</label>
        <input type="number" name="width" id="width" min="100" class="form-control<?php echo $errors['width']; ?>" value="<?php echo $values['width']; ?>" placeholder="0" required="required">
        <label for="mframe" class="col-form-label">Материал рамки</label>
        <select name="mframe" id="mframe" class="form-control<?php echo $errors['mframe']; ?>">
            <option value="Алюминий" <?php echo $values['mframe1']; ?>>Алюминий</option>
            <option value="CHROMATECH ultra" <?php echo $values['mframe2']; ?>>CHROMATECH ultra</option>
        </select>
        <label for="quantity" class="col-form-label">Кол-во, шт.</label>
        <input type="number" name="quantity" id="quantity" class="form-control<?php echo $errors['quantity']; ?>" min="1" value="<?php echo $values['quantity']; ?>" placeholder="1" required="required">
        <label for="marking" class="col-form-label">Маркировка</label>
        <input type="text" name="marking" id="marking" class="form-control<?php echo $errors['marking']; ?>" value="<?php echo $values['marking']; ?>" minlength="1" required="required">
    </div>
    <div class="fixed-bottom btn-toolbar pb-2 justify-content-center">
        <div class="btn-group col-4 col-sm-4 col-md-4 col-lg-3 col-xl-2 px-1">
            <button type="submit" name="btnAdd" class="btn btn-primary btn-block btn-lg border-dark">Добавить</button>
        </div>
        <div class="btn-group col-4 col-sm-4 col-md-4 col-lg-3 col-xl-2 px-0">
            <a href="/m/cart" class="btn btn-primary btn-block btn-lg border-dark">Корзина (<?php echo $cartCount; ?>)</a>
        </div>
        <div class="btn-group col-4 col-sm-4 col-md-4 col-lg-3 col-xl-2 px-1 ">
            <a href="/m/order" class="btn btn-primary btn-block btn-lg border-dark">Оправить</a>
        </div>
    </div>
</form>