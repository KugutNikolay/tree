<?php

namespace core;

class View
{
	public function render($viewPath, $params)
	{
		extract($params);
		ob_start();
		include $viewPath;
		return ob_get_clean();
	}
}