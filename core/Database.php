<?php

namespace core;

use PDO;
use PDOException;

class Database
{
	private static $instance;
	private        $pdo;

	private function __construct()
	{
		$dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8';

		$options = [
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
			PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
			PDO::ATTR_EMULATE_PREPARES => false,
		];

		try {
			$this->pdo = new PDO($dsn, DB_USER, DB_PASSWORD, $options);
		} catch (PDOException $e) {
			throw new PDOException($e->getMessage(), (int)$e->getCode());
		}
	}

	public static function getInstance()
	{
		if (!isset(self::$instance)) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function getConnect()
	{
		return $this->pdo;
	}

}