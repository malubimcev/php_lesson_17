<?php
 
    class Router
    {
        private $dirController = 'controller/';//каталог контроллеров по умолчанию
        private $action = '';
        private $controller = '';
        
	public function __construct($dirController)
	{
            $this -> dirConroller = $dirController;
	}
        
        public function start()
        {
            $route = NULL;
            $delimiter = '?/';
            $params = [];
            $controllerName = 'UserController';//контроллер по умолчанию
            if (($pos = strpos($_SERVER['REQUEST_URI'], $delimiter)) !== false) {
                $route = substr($_SERVER['REQUEST_URI'], $pos + strlen($delimiter));
            }
            if (is_null($route)) {
                $route = $_SERVER['REQUEST_URI'];
            }
            $route = explode('/', $route);
            if (!empty($route[0])) {
                $controllerName = ucfirst($route[0]) . 'Controller';
            }
            $controllerFile = $this -> dirController . $controllerName . '.php';
            $action = $route[1];
            $params = $this -> getParams();
            if (!empty($route[2])) {
                $params = $route[2];    
            }
            echo '<br> +++ params='; var_dump($params); echo '+++<br>';
            if (is_file($controllerFile)) {
                require_once $controllerFile;
                echo 'exist file='.$controllerFile.'<br>';
                if (class_exists($controllerName)) {
                    $this -> controller = new $controllerName();
                    echo 'exist controller='.$controllerName.'<br>';
                    if (method_exists($this -> controller, $action)) {
                        //$this -> controller -> $action($params);
                        echo 'exist action='.$action.'<br>';
                    } else {echo 'does not exist action='.$action.'<br>';}
                }
            }
        }
        
        private function getParams()
        {
            $params = [];
            switch($_SERVER['REQUEST_METHOD']) { 
                case "GET" :
                    $params = $_GET;
                    break;
                case "POST" :
                    $params = $_POST;
                    break;
                default : 
                    $params = $_GET;
                break;
            }
            return $params;
        }
    }//end class Router