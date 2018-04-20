<?php
/**
 * класс маршрутизатор, подбирает нужный контролер для обработки данных
 */
class App_Application
{
    private $route;
    private $path;

    /**
     * получить маршрут .htaccess формирует ссылку таким образом,
     * что в параметры гет запроса попадает требуемый маршрут
     */
    public function __construct()
    {
        $route = explode ('/', $_REQUEST['route']);
        switch ($route[0])
        {
            case '':
                $this->path = 'App/';
                $this->route = 'index';
                break;
            default :
                $this->path = 'App/';
                $this->route = $route[0];
        }
    }

    /**
     * получить контролер
     */
    private function getController()
    {
        $controller = $this->path . 'Controllers/' . $this->route . '.php';
        if (!file_exists($controller))
        {
            $controller = $this->path . 'Controllers/404.php';
        };
        return $controller;
    }

    /**
     * получить представление для контролера
     */
	public function getView()
    {
        //$route = $this->getRoute();
        $view = $this->path . 'Views/' . $this->route . '.php';
        if(!file_exists($view)) {
            $view = $this->path . 'Views/404.php';
        }
        return $view;
    }

    /**
     * запуск процесса обработки данных
     * создаем экземпляр класса контролера
     * запускаем контролер на выполнение (index() должна быть у любого контролера)
     * получаем переменные контролера
     */
    public function Run()
    {

        $controller = $this->getController();
        $cl = explode('.', $controller);
        $cl = $cl[0];
        //заменяем в пути слеши на подчеркивания, таким образом получая название класса
        $name_contr = str_replace("/", "_", $cl);
        $contr = new $name_contr;
        $contr->index();
        $member = $contr->member;
        return $member;
    }
}