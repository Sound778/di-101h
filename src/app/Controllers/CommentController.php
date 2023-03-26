<?php

namespace App\Controllers;

use App\Models\Comment;

class CommentController extends BaseController
{
	private $model;

	public function __construct()
	{
		$this->model = new Comment();
	}

	/**
	 * Returns an array of comment data
	 *
	 * @return mixed
	 */
	public function index()
	{
		$viewData = [
			'comments' => $this->model->orderBy('id', 'DESC')->paginate(3),
			'pager' => $this->model->pager,
		];

		return view('comment_chart', $viewData);
	}


	/**
	 * Creates a new comment
	 *
	 * @return mixed
	 */
	public function add()
	{
		$params = $this->request->getPost();
		$data = [
			'name' => trim(strip_tags($params['creator_email'])),
			'text' => trim(strip_tags($params['message_text'])),
			'date' => $params['created_at']
		];

		if ($this->model->insert($data, false)) {
			return redirect()->route('show_comments');
		} else {
			$errors = $this->model->errors();
			return view('comment_chart', compact('errors'));
		}
	}

	/**
	 * Deletes the comment
	 *
	 * @return mixed
	 */
	public function delete()
	{
		$msg = [
			'status' => 'error',
			'text' => '',
		];
		if ($this->request->isAJAX()) {
			$params = $this->request->getPost();
			if ($this->model->delete((int)$params['id'])) {
				$msg['status'] = 'OK';
			} else {
				$msg['text'] = 'Комментарий не был удален!';
			}
			return json_encode($msg);

		} else {
			$this->ajaxNeeded();
		}
	}

	public function ajaxNeeded() {
		$errors[] = 'Необходим Ajax (согласно ТЗ)';
		return view('comment_chart', compact('errors'));
	}
}
