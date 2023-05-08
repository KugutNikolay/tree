<?php

namespace core;

use Exception;

class App
{
	public function run()
	{

		$parts = explode('/', ($_GET['route'] ?? ''));

		$controller = empty($parts[0]) ? 'tree' : $parts[0];
		$action = empty($parts[1]) ? 'index' : $parts[1];
		$params = array_slice($parts, 2);

		$controllerClass = 'app\controllers\\' . str_replace('-','', ucwords($controller, '-')) . 'Controller';

		$controllerInstance = new $controllerClass();

		$actionMethod = 'action' . str_replace('-','', ucwords($action, '-'));

		$response = call_user_func_array([$controllerInstance, $actionMethod], $params);

		echo $response;
	}
}