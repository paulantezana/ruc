<?php

class Router
{
    private $controller;
    private $method;
    private $param;

    public function __construct()
    {
        $this->matchRoute();
    }

    private function matchRoute()
    {
        $url = explode('/', URL);

        $this->method = !empty($url[2]) ? $url[2] : 'home';

        if (isset($_SESSION[SESS_KEY])) {
            $this->controller = !empty($url[1]) ? $url[1] : 'Home';
        } else {
            $this->controller = 'Page';
        }

        $this->controller = ucwords($this->controller) . 'Controller';
        if (!is_file(CONTROLLER_PATH . "/{$this->controller}.php")) {
            $this->controller = 'PageController';
            $this->method = 'error404';
        }

        require_once(CONTROLLER_PATH . "/{$this->controller}.php");
        if (!method_exists($this->controller, $this->method)) {
            $this->controller = 'PageController';
            $this->method = 'error404';
            require_once(CONTROLLER_PATH . "/{$this->controller}.php");
        }
    }

    public function run()
    {
        $database = new Database();
        $controller = new $this->controller($database->getConnection());
        $method = $this->method;
        $controller->$method($this->param);
    }
}
