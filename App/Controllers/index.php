<?php
/**
 * контролер главной страницы
 */
class App_Controllers_Index  extends App_BaseController
{

    function index()
    {
        $model = new App_Models_Index();
        if (isset($_REQUEST['btnAdd']))
        {
            App_Session::startSession(true);
            if($model->checkProduct())
            {
                $model->addProduct();
                header("Location: /index");
                exit;
            }
        }
        $this->errors = $model->getErrors();
        $this->values = $model->getProduct();
    }
}