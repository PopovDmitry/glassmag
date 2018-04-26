<?php
/**
 * контролер страницы оформления заказа
 */
class App_Controllers_Order  extends App_BaseController
{
    function index()
    {
        if(isset($_REQUEST))
        {
            App_Session::startSession(true);
        }
        $model = new App_Models_Order();
        if (isset($_REQUEST['btnOrder']) && is_array($_SESSION['cart']))
        {
            if($model->checkOrder())
            {
                if($model->sendOrder())
                {
                    App_Cart::delCart();
                    header("Location: /m/orderok");
                    exit;
                }
            }
        }
        $this->errors = $model->getErrors();
        $this->values = $model->getOrder();
    }
}