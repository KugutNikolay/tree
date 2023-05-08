<?php

namespace app\models;

use core\Database;
use core\DbTrait;
use core\Model;
use PDO;


class Node extends Model
{
	use DbTrait;

	private $id;
	private $parent_id;
	private $text;

	public static function getTableName()
	{
		return 'nodes';
	}

	public static function getRootNode()
	{
		$query = Database::getInstance()->getConnect()->query('SELECT * FROM ' . self::getTableName() . ' WHERE parent_id IS NULL');
		return $query->fetchObject(self::class)?? null;
	}

	public function getParent()
	{
		$query = Database::getInstance()->getConnect()->prepare('SELECT * FROM ' . self::getTableName() . ' WHERE id = :parent_id');
		$query->bindParam('parent_id', $this->parent_id, PDO::PARAM_INT);
		$query->execute();
		return $query->fetchObject(self::class)?? null;

	}

	public function getChilds()
	{
		return $this->getQueryChild()->fetchAll(PDO::FETCH_CLASS, self::class)?? null;
	}

	public function isChilds()
	{
		return !empty($this->getQueryChild()->rowCount());
	}

	private function getQueryChild() {
		$query = Database::getInstance()->getConnect()->prepare('SELECT * FROM ' . self::getTableName() . ' WHERE parent_id = :id');
		$query->bindParam('id', $this->id, PDO::PARAM_INT);
		$query->execute();
		return $query;
	}

	public function save() {
		if ($this->id === null) {
			$query = Database::getInstance()->getConnect()->prepare('INSERT INTO ' . self::getTableName() . ' (parent_id, text) VALUES(:parent_id, :text)');
			$query->bindParam('parent_id', $this->parent_id, PDO::PARAM_INT);
		}
		else {
			$query = Database::getInstance()->getConnect()->prepare('UPDATE ' . self::getTableName() . ' SET text = :text WHERE id = :id');
			$query->bindParam('id', $this->id, PDO::PARAM_INT);
		}

		$query->bindParam('text', $this->text);
		$query->execute();
		$this->id = $this->id === null ? $this->db->lastInsertId() : $this->id;

		return $query->rowCount() === 1;
	}

	public function delete() {
		$query = Database::getInstance()->getConnect()->prepare('DELETE FROM ' . self::getTableName() . ' WHERE id = :id');
		$query->bindParam('id', $this->id, PDO::PARAM_INT);
		$query->execute();
		return $query->rowCount() === 1;
	}

	/**
	 * @return int
	 */
	public function getId(): int
	{
		return $this->id;
	}

	/**
	 * @param int $id
	 */
	public function setId(int $id): Node
	{
		$this->id = $id;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getParentId():? int
	{
		return $this->parent_id;
	}

	/**
	 * @param int|null $parent_id
	 */
	public function setParentId(?int $parent_id): Node
	{
		$this->parent_id = $parent_id;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getText(): string
	{
		return $this->text;
	}

	/**
	 * @param string $text
	 */
	public function setText(string $text): Node
	{
		$this->text = $text;
		return $this;
	}

}