<?php

namespace core;

use PDO;

trait DbTrait
{

	public static function getTableName()
	{
		return '';
	}

	public static function findOne($id) :? self
	{
		$query = Database::getInstance()->getConnect()->prepare('SELECT * FROM ' . self::getTableName() . ' WHERE id = :id');
		$query->bindParam('id', $id, PDO::PARAM_INT);
		$query->execute();
		return $query->fetchObject(self::class)?? null;
	}
}