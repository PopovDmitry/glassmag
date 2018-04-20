<?php
/**
 * Основное приложение
 */

//Error_Reporting(E_ALL & ~ E_NOTICE); //не выводить предупреждения
require_once "App/Autoload.php";

App_Session::startSession();
App_Session::restoreSession();
App_Cart::restoreCartCount();

$router = new App_Application;
$member = $router->Run();

$member['init'] = 0;
foreach ($member as $key => $value) {
    $$key = $value;
}

$cartCount = App_Cart::getCartCount();

include ($_SERVER['DOCUMENT_ROOT'] . '/template/template.php');