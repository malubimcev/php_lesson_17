<?php

/**
 * Первый уровень понимания роутеров.
 * Намерено сделано через if и else
 * Пример урла: /?c={controller}&a={action}&{param1}={value1}&{param2}={value2}
 * /?c=book&a=update&id=1
  /?c=book&a=add
 */
if (! isset($_GET['c']) || ! isset($_GET['a'])) {
    $controller = 'book';
    $action = 'list';
} else {
    $controller = $_GET['c'];
    $action = $_GET['a'];
}
if ($controller == 'book') {
    include 'controller/BookController.php';
    $bookController = new BookController();
    if ($action == 'list') {
        $bookController->getList();
    } elseif ($action == 'add') {
        $bookController->add();
    } elseif ($action == 'update') {
        $bookController->update();
    } elseif ($action == 'delete') {
         $bookController->delete();
    }
}

/**
 * Второй уровень понимания роутеров.
 * Сделаем более универсальным
 * Пример урла: /?c={controller}&a={action}&{param1}={value1}&{param2}={value2}
 * /?c=book&a=update&id=1
 */

$controllerText = $controller . 'Controller';
$controllerFile = 'controller/' . ucfirst($controllerText) . '.php';
if (is_file($controllerFile)) {
    include $controllerFile;
    if (class_exists($controllerText)) {
        $controller = new $controllerText();
        if (method_exists($controller, $action)) {
            $controller->$action();
        }
    }
}

/**
 * Третий уровень понимания роутеров.
 * Уберём проверки файлов. Добавим настройки
 * Но помним, что многие вещи не реализованны
 * Пример урла: /?r={controller}/{action}&{param1}={value1}&{param2}={value2}
 * ?r=/book/update/id/(\d+)/&utm_sourсe=yandex
 */
class Router
{
	private $dirConroller = '';
	private $urls = [];
	function __construct($dirConroller)
	{
		$this->dirConroller = $dirConroller;
	}
	/**
	 * Добавление роутеров
	 * @param $url урл
	 * @param $controllerAndAction пример: BookController@getUpdate
	 */
	public function get($url, $controllerAndAction, $params = [])
	{
		$this->add('GET', $url, $controllerAndAction, $params);
	}
	/**
	 * Добавление роутеров
	 * @param $url урл
	 * @param $controllerAndAction пример: BookController@postUpdate
	 */
	public function post($url, $controllerAndAction, $params = [])
	{
		$this->add('POST', $url, $controllerAndAction, $params);
	}
	/**
	 * Добавление роутеров
	 * @param $url урл
	 * @param $controllerAndAction пример: BookController@list
	 */
	public function add($method, $url, $controllerAndAction, $params)
	{
		list($controller, $action) = explode('@', $controllerAndAction);
		$this->urls[$method][$url] = [
			'controller' => $controller,
			'action' => $action,
			'params' => $params
		];
	}
	/**
	 * Подключение контроллеров
	 * @param $url текущий урл
	 */
	public function run($currentUrl)
	{
		if (isset($this->urls[$_SERVER['REQUEST_METHOD']])) {
			foreach ($this->urls[$_SERVER['REQUEST_METHOD']] as $url => $urlData) {
				if (preg_match('(^'.$url.'$)', $currentUrl, $matchList)) {
					$params = [];
					foreach ($urlData['params'] as $param => $i) {
						$params[$param] = $matchList[$i];
					}
					include $this->dirConroller.$urlData['controller'].'.php';
					$controller = new $urlData['controller']();
					if ($_SERVER['REQUEST_METHOD'] == 'POST') {
						$controller->$urlData['action']($params, $_POST);
					} else {
						$controller->$urlData['action']($params);
					}
				}
			}
		}
	}
}
$router = new Router('controller/');
$router->get('/', 'BookController@getList');
$router->get('/book/add/', 'BookController@getAdd');
$router->post('/book/add/', 'BookController@postAdd');
$router->get('/book/update/id/(\d+)/', 'BookController@getUpdate', ['id' => 1]);
$router->post('/book/update/id/(\d+)/', 'BookController@postUpdate', ['id' => 1]);
$router->get('/book/delete/id/(\d+)/', 'BookController@getDelete', ['id' => 1]);
/*
Удаляем "/?", потому что не сделали настройки на серверах
 */
$currentUrl = str_replace('/?r=', '', $_SERVER['REQUEST_URI']);
/*
Если добавить конфиг в
Apache
	Options +FollowSymLinks
	RewriteEngine On
	RewriteRule ^(.*)$ index.php [NC,L]
Nginx:
	location / {
		try_files $uri $uri/ /index.php?$query_string;
	}
то:
$currentUrl = $_SERVER['REQUEST_URI'];
*/
$router->run($currentUrl);
