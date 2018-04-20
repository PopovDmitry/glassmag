<?php
/**
 * Восстановление сессии из куки
 */
class App_Session{

    static $started = 0;

    /**
     * @param $force bool принудительный старт сессии
     */
    static function startSession($force=false)
    {
        if (isset($_REQUEST['PHPSESSID']) || isset($_COOKIE['PHPSESSID'])  || $force)
        {
            /* Переменная $started нужна для избежания повторого вызова session_start */
            if (!self::$started)
            {
		session_set_cookie_params(14 * 24 * 60 * 60,'/', $_SERVER['HTTP_HOST']);
                session_start();
                self::$started = 1;
            }
        }
    }

    /**
     * получает данные из кук в сессию
     */
    static function restoreSession()
    {
        if (self::$started && is_null($_SESSION))
        {
            $_SESSION = isset($_COOKIE['cart']) ?  unserialize(stripslashes($_COOKIE['cart'])) : null;
            $_SESSION = isset($_COOKIE['order']) ?  unserialize(stripslashes($_COOKIE['order'])) : null;
        }
    }

    /**
     * сохраняет данные сессии в куки
     */
    static function saveSession()
    {
        if (self::$started)
        {
            $cookieLife = time() + 3600 * 24 * 14;
            setcookie("cart", serialize($_SESSION['cart']), $cookieLife);
            setcookie("order", serialize($_SESSION['order']), $cookieLife);
        }
    }
}
