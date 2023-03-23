<?php

namespace App\Controllers;

use App\Models\Comment as CommentModel;

class Comment extends BaseController
{
	private $error = [];
	private const DEFAULT_LIMIT = 3;
	private const DEFAULT_PAGE = 1;
	private const DEFAULT_SORT_BY = 'id';
	private const DEFAULT_SORT_DIR = 'asc';
	private const AVAILABLE_SORT_BY = ['id', 'date'];
	private const AVAILABLE_SORT_DIR = ['asc', 'desc'];

	public function add()
	{
		if ($this->validatePost()) {
			$comment_model = new CommentModel();
			$data = [
				'name'    => $this->request->getPost('name'),
				'text'	  => $this->request->getPost('text'),
				'date'	  => $this->request->getPost('date')
			];

			$result = $comment_model->insert($data);

			$this->response->setStatusCode(200);
			$this->response->setJSON(['comment_id' => $result]);
		} else {
			$this->response->setStatusCode(400);
			$this->response->setJSON(['message' => $this->error]);
		}

		return $this->response;
	}

	public function getList()
	{
		if ($this->validateGet()) {
			$comment_model = new CommentModel();

			$page = $this->request->getGet('page') ?? self::DEFAULT_PAGE;
			$limit = $this->request->getGet('limit') ?? self::DEFAULT_LIMIT;
			$sort_by = $this->request->getGet('sort_by') ?? self::DEFAULT_SORT_BY;
			$sort_dir = $this->request->getGet('sort_dir') ?? self::DEFAULT_SORT_DIR;

			$result = $comment_model
				->asArray()
				->orderBy($sort_by, $sort_dir)
				->findAll($limit, ($limit * ($page - 1)));

			$this->response->setStatusCode(200);
			$this->response->setJSON($result);
		} else {
			$this->response->setStatusCode(400);
			$this->response->setJSON(['message' => $this->error]);
		}

		return $this->response;
	}

	public function getPages()
	{
		if ($this->validateGet()) {
			$comment_model = new CommentModel();
			$pager = service('pager');
			$page    = (int) ($this->request->getGet('page') ?? self::DEFAULT_PAGE);
			$limit = $this->request->getGet('limit') ?? self::DEFAULT_LIMIT;
			$total   = $comment_model->getTotalComments();
			$pager_links = $pager->makeLinks($page, $limit, $total, 'comments');

			return $pager_links;
		}

		return '';
	}

	public function delete()
	{
		if ($this->validateDelete()) {
			$comment_model = new CommentModel();

			$result = $comment_model->delete($this->request->getGet('id'));

			if ($result && $result->connID->affected_rows > 0) {
				$this->response->setStatusCode(200);
				$this->response->setJSON(['message' => 'Удаление успешно']);
			} else {
				$this->response->setStatusCode(404);
				$this->response->setJSON(['message' => 'Элемент не найден']);
			}
		} else {
			$this->response->setStatusCode(400);
			$this->response->setJSON(['message' => $this->error]);
		}

		return $this->response;
	}

	private function validatePost()
	{
		if ($this->request->getMethod() !== 'post') {
			throw new \Exception('Метод не доступен');
		}

		if (empty($this->request->getPost('name'))) {
			$this->error[] = [
				'message' => 'Email пуст',
				'selector' => 'name'
			];
		}

		if (!$this->isValidEmail($this->request->getPost('name'))) {
			$this->error[] = [
				'message' => 'Email не валиден',
				'selector' => 'name'
			];
		}

		if (empty($this->request->getPost('text'))) {
			$this->error[] = [
				'message' => 'Комментарий пуст',
				'selector' => 'text'
			];
		}

		if (empty($this->request->getPost('date'))) {
			$this->error[] = [
				'message' => 'Дата пуста',
				'selector' => 'date'
			];
		}

		return !$this->error;
	}

	private function validateGet()
	{
		if ($this->request->getMethod() !== 'get') {
			throw new \Exception('Метод не доступен');
		}

		if (!empty($this->request->getGet('page')) && (int)$this->request->getGet('page') <= 0) {
			$this->error[] = 'Параметр page не валиден';
		}

		if (!empty($this->request->getGet('limit')) && (int)$this->request->getGet('limit') <= 0) {
			$this->error[] = 'Параметр limit не валиден';
		}

		if (!empty($this->request->getGet('sort_by')) && !in_array($this->request->getGet('sort_by'), self::AVAILABLE_SORT_BY)) {
			$this->error[] = 'Параметр sort_by не валиден';
		}

		if (!empty($this->request->getGet('sort_dir')) && !in_array($this->request->getGet('sort_dir'), self::AVAILABLE_SORT_DIR)) {
			$this->error[] = 'Параметр sort_dir не валиден';
		}

		return !$this->error;
	}

	private function validateDelete()
	{
		if ($this->request->getMethod() !== 'delete') {
			throw new \Exception('Метод не доступен');
		}

		if (empty($this->request->getGet('id'))) {
			$this->error[] = 'Параметр id пуст';
		}

		if (!empty($this->request->getGet('id')) && (int)$this->request->getGet('id') <= 0) {
			$this->error[] = 'Параметр id не валиден';
		}

		return !$this->error;
	}

	private function isValidEmail($email)
	{
		return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
	}
}
