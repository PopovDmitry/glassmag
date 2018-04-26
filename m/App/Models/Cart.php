<?php
/**
 * Модель вывода корзины
 */
class App_Models_Cart
{
    static function getListProduct()
    {
        foreach ($_SESSION['cart'] as $key => $value)
        {
            $product = explode(";", $value);
            $description = "Окно: артикул " . $product[1]
                . ", размер(ВхШ) " . $product[2] . " x " . $product[3]
                . ", рамка - " . $product[4]
                . ", количество(шт) - " . $product[5]
                .", маркировка - " . $product[6];
            $cart[$key] = $description;
        }
        return $cart;
    }
} 