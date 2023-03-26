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
		$comments = $this->model->orderBy('id', 'desc')->findAll(3);
		return view('comment_chart', compact('comments'));
	}


	/**
	 * Creates a new comment
	 *
	 * @return mixed
	 */
	public function create()
	{
		//
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
			$query = $this->request->getPost();
			if($this->model->delete((int)$query['id'])) {
				$msg['status'] = 'OK';
			} else {
				$msg['text'] = 'Комментарий не был удален!';
			}
			return json_encode($msg);

		} else {
			$error = 'Incorrect Request';
			return view('welcome_message', compact('error'));
		}
	}
}
