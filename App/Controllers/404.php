<?php
/**
 * контролер обрабатывает данные несуществующей страницы
 */
class App_Controllers_404 extends App_BaseController
{

    public function index()
    {
    	header("HTTP/1.0 404 Not Found");
    }
}