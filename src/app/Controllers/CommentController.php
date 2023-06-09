<?php

namespace App\Controllers;

use App\Models\Comment;

class CommentController extends BaseController
{
	private $model;
	private $order_by = [
		'query' => [
			1 => ['id', 'ASC'],
			2 => ['id', 'DESC'],
			3 => ['date', 'ASC'],
			4 => ['date', 'DESC'],
		],
		'selector' => [
			1 => 'По id &uarr;',
			2 => 'По id &darr;',
			3 => 'По дате &uarr;',
			4 => 'По дате &darr;',
		],
	];


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
		$session = session();
		$sort_order = $session->get('comments_order') ?? 1;
		$viewData = [
			'comments' => $this->model->orderBy(implode(' ', $this->order_by['query'][$sort_order]))->paginate(3),
			'pager' => $this->model->pager,
			'selector' => $this->order_by['selector'],
			'current_sort_order' => $sort_order,
		];

		return view('comment_chart', $viewData);
	}


	/**
	 * Creates a new comment
	 *
	 * @return \CodeIgniter\HTTP\RedirectResponse
	 */
	public function add()
	{
		$session = session();
		$params = $this->request->getPost();
		$data = [
			'name' => trim(strip_tags($params['creator_email'])),
			'text' => trim(strip_tags($params['message_text'])),
			'date' => $params['created_at']
		];

		if (!$this->model->insert($data, false)) {
			$session->setFlashdata('flash_errors', $this->model->errors());
		}
		return redirect()->route('show_comments');
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
			return $this->ajaxNeeded();
		}
	}

	/**
	 * displays alert when ajax request is needed
	 *
	 * @return \CodeIgniter\HTTP\RedirectResponse
	 */
	public function ajaxNeeded() {
		$session = session();
		$session->setFlashdata('flash_errors', ['AJAX needed!']);
		return redirect()->route('show_comments');
	}

	/**
	 * sets comments sorting
	 *
	 * @return \CodeIgniter\HTTP\RedirectResponse
	 */
	public function sort() {
		$params = $this->request->getPost();
		$session = session();
		$session->set('comments_order', (int)$params['order_by']);
		return redirect()->route('show_comments');
	}
}
