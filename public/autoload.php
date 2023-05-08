<?php
function autoload($class)
{
	$path = __DIR__ . '/../' . str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';
	if (file_exists($path)) {
		require_once $path;
	}
}

spl_autoload_register('autoload');