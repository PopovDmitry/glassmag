<?php
/**
 * Автоматическая загрузка классов
 */

spl_autoload_register(function ($class_name)
{
    $path = str_replace("_", "/", $class_name);
    include_once ($_SERVER['DOCUMENT_ROOT'] . '/' . $path . '.php');
});
