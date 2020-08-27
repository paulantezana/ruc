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
            if (preg_match('/^\/api\/v1/', URL)){
                $urlPath = '/' . trim(preg_replace('/^\/api\/v1/', '', URL), '/');
                $this->controller = 'Api1';
                $this->method = trim($urlPath,'/');
            } else if(URL === '/consultas/api.php') { // Solor para dar soporte a las antiguas versiones
                $this->controller = 'Api1';
                $this->method = 'oldApi';
            } else {
                $this->controller = 'Page';
            }
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
        try {
            $database = new Database();
            $controller = new $this->controller($database->getConnection());
            $method = $this->method;
            $controller->$method($this->param);
        } catch (Exception $e) {
            $error = "PHP Fatal error | URL : " . HOST . URI . " | ERROR index : \n{$e->getMessage()}\n{$e->getTraceAsString()}";
            echo '<pre>' . $error . '</pre>';
            error_log($error, 3,  ROOT_DIR . '/files/errors.log');
        }
    }
}
