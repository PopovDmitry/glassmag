<?php
/**
 * контролер корзины
 */

class App_Controllers_Cart  extends App_BaseController
{

    function index()
    {
        if (isset($_REQUEST['btnDel']))
        {
            App_Cart::refreshCart();
        }

        if (isset($_SESSION['cart']))
        {
            $this->cart = App_Models_Cart::getListProduct();
        }
    }
}