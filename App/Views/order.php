<h2>Оформить заказ</h2>

<form action="/order" method="post">
    <div class="form-group pb-5">
        <div class="form-check-inline<?php echo $errors['delivery']; ?>">
            <legend class="col-form-label">Вид доставки&nbsp;&nbsp;&nbsp;&nbsp;</legend>
            <label for="delivery0" class="form-check-label form-check-inline p-3">
                <input type="radio" name="delivery" id="delivery0" class="form-check-input" value="0" <?php echo $values['delivery0']; ?>>&nbsp;&nbsp;Компанией&nbsp;&nbsp;&nbsp;</label>
            <label for="delivery1" class="form-check-label form-check-inline p-3">
                <input type="radio" name="delivery" id="delivery1" class="form-check-input" value="1" <?php echo $values['delivery1']; ?>>&nbsp;&nbsp;Самовывоз</label>
        </div>
        <div>
        <label for="order-date" class="col-form-label">Дата выполнения заказа</label>
        <input type="date" name="order-date" id="order-date" class="form-control<?php echo $errors['order-date']; ?>" min="<?php echo $values['order-date']; ?>" value="<?php echo $values['order-date']; ?>" required="required">
        <label for="order-name" class="col-form-label">Ваше имя (Название компании)</label>
        <input type="text" name="order-name" id="order-name" class="form-control<?php echo $errors['order-name']; ?>" minlength="5" maxlength="50" value="<?php echo $values['order-name']; ?>" placeholder="Иванов Иван Иванович" required="required">
        <label for="order-phone" class="col-form-label">Номер телефона</label>
        <input type="tel" name="order-phone" id="order-phone" class="form-control<?php echo $errors['order-phone']; ?>" value="<?php echo $values['order-phone']; ?>" pattern="^((8|\+7)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}$" required="required">
        <label for="order-email" class="col-form-label">Ваш e-mail</label>
        <input type="email" name="order-email" id="order-email" class="form-control<?php echo $errors['order-email']; ?>" aria-describedby="emailHelp" placeholder="my@my.com" value="<?php echo $values['order-email']; ?>" size="52" required="required">
        </div>
    </div>
    <div class="fixed-bottom btn-toolbar pb-2 justify-content-center">
        <div class="btn-group col-4 col-sm-4 col-md-4 col-lg-3 col-xl-2 px-1">
            <a href="/" class="btn btn-primary btn-block btn-lg border-dark">Добавить</a>
        </div>
        <div class="btn-group col-4 col-sm-4 col-md-4 col-lg-3 col-xl-2 px-0">
            <a href="/cart" class="btn btn-primary btn-block btn-lg border-dark">Корзина <?php echo $cartCount; ?></a>
        </div>
        <div class="btn-group col-4 col-sm-4 col-md-4 col-lg-3 col-xl-2 px-1">
            <button type="submit" name="btnOrder" class="btn btn-primary btn-block btn-lg border-dark">Оправить</button>
        </div>
    </div>
</form>


