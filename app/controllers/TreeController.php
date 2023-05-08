<?php

namespace app\controllers;

use app\models\Node;
use core\Controller;
use HttpException;

class TreeController extends Controller
{

	public function actionIndex() {
		return $this->render('tree/index', ['rootNode' => Node::getRootNode()]);
	}

	public function actionLoadChild() {
		if(empty($_POST['id'])) {
			throw new HttpException('Not Found');
		}

		$model = Node::findOne($_POST['id']);
		if (!$model) {
			throw new HttpException('Not Found');
		}

		$childs = $model->getChilds();
		if (!$childs) {
			throw new HttpException('Not Found');
		}

		$results = ['status' => 'success', 'childs' => []];
		/** @var  Node $child */
		foreach ($childs as $child) {
			$results['childs'][] = [
				'id' => $child->getId(),
				'parent_id' => $child->getParentId(),
				'text' => $child->getText(),
				'isChilds' => $child->isChilds()
			];
		}
		$this->renderJson($results);
	}

	public function actionSave()
	{
		if (empty($_POST['text'])) {
			throw new HttpException('Wrong data');
		}

		if (!empty($_POST['id'])) {
			$model = Node::findOne($_POST['id']);
			if (!$model) {
				throw new HttpException('Not Found');
			}
		}
		else {
			$root = Node::getRootNode();
			if($root && empty($_POST['parent_id'])) {
				throw new HttpException('Wrong data');
			}
			$model = new Node();
			$model->setParentId((empty($_POST['parent_id'])? null : $_POST['parent_id']));
		}
		$model->setText($_POST['text']);

		if ($model->save()) {
			$this->renderJson([
				'status' => 'success',
				'id' => $model->getId(),
				'parent_id' => $model->getParentId(),
				'isChilds' => $model->isChilds(),
				'text' => $model->getText(),

			]);
		}
		$this->renderJson(['status' => 'false']);
	}

	public function actionDelete() {
		if (!empty($_POST['id'])) {
			$model = Node::findOne($_POST['id']);
			if (!$model) {
				throw new HttpException('Not Found');
			}
			$parent_id = $model->getParentId();
			if ($model->delete()) {
				$this->renderJson([
					'status' => 'success',
					'parent_id' => $parent_id
				]);
			}
		}
		throw new HttpException('Bad request');
	}
}