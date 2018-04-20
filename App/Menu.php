<?php
/**
 * Генератор меню
 */

//namespace App;


class App_Menu
{
    private $item;

    function __construct()
    {
        $item = parse_ini_file("config/menu.ini");

        if($item == false)
        {
            throw new Exception("Ошибка конфигурации.");
        }
        echo "<pre>";
        var_dump($item);
        echo "</pre>";
    }


    public function getMenu()
    {
        $menu = '<div class="fixed-bottom btn-toolbar pb-2>';

            /*<div class="btn-group col-4 px-1">
                <a href="/" class="btn btn-primary btn-block btn-lg border-dark">Добавить</a>
            <button type="submit" name="btnAdd" class="btn btn-primary btn-block btn-lg border-dark">Добавить</button>
            </div>
            <div class="btn-group col-4 px-0">
                <a href="/cart" class="btn btn-primary btn-block btn-lg border-dark">Корзина <?php echo $cartCount; ?></a>
        </div>
        <div class="btn-group col-4 px-1">
            <a href="/order" class="btn btn-primary btn-block disabled btn-lg border-dark disabled">Оправить</a>
            <button type="submit" name="btnbtnOrder" class="btn btn-primary btn-block btn-lg border-dark">Оправить</button>
        </div>*/
        $menu .= '</div>';
        return $menu;
    }
}