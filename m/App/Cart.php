<?php
/**
 * Клас моделирует корзину
 */

class App_Cart
{
    private static $cartCount = 0;

    /**
     * @return int
     */
    public static function getCartCount ()
    {
        return self::$cartCount;
    }

    /**
     * @param $product string сериализованный заказ
     */
    public static function addToCart($product)
    {
        $_SESSION['cart'][self::$cartCount] = $product;
        self::$cartCount++;
        App_Session::saveSession();
    }

    /**
     * @param $order string сериализованный заказ
     */
    public static function refreshCart()
    {
        foreach ($_SESSION['cart'] as $key => $value) {
            if (isset($_REQUEST['btnDel'][$key]))
            {
                unset($_SESSION['cart'][$key]);
            }
        }
        $n = 0;
        foreach ($_SESSION['cart'] as $key)
        {
            if ($key != $n)
            {
                $key = $n;
            }
            $n++;
        }
        self::restoreCartCount();
        App_Session::saveSession();
    }

    public static function restoreCartCount ()
    {
        self::$cartCount = (is_array($_SESSION['cart'])) ? count($_SESSION['cart']) : 0;
    }

    public static function delCart ()
    {
        unset($_SESSION['cart']);
        self::$cartCount = 0;
        App_Session::saveSession();
    }
}