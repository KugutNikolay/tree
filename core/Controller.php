<?php

namespace core;

class Controller
{
	protected $view;

	public function __construct()
	{
		$this->view = new View();
	}

	protected function render($view, $params = [])
	{
		$viewPath = __DIR__ . '/../app/views/' . $view . '.php';

		return $this->view->render($viewPath, $params);
	}

	protected function renderJson($params = [])
	{
		header('Content-Type: application/json');
		echo json_encode($params);
		exit(0);
	}

}