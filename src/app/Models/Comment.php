<?php

namespace App\Models;

use CodeIgniter\Model;

class Comment extends Model
{
	protected $table                = 'comments';
	protected $primaryKey           = 'id';
	protected $returnType           = 'array';
	protected $allowedFields        = ['name', 'text', 'date'];
	protected $validationRules = [
		'name' => 'required|valid_email|min_length[7]',
		'text' => 'required|string',
		'date' => 'required|date|max_length[10]',
	];
	protected $validationMessages = [
		'name' => [
			'required' => 'Поле "Электронная почта" обязательно для заполнения!',
			'valid_email' => 'Неверный формат электронной почты!'
		],
		'text' => [
			'required' => 'Поле "Комментарий" обязательно для заполнения!'
		],
		'date' => [
			'required' => 'Поле "Дата создания комментария" обязательно для заполнения!',
			'date' => 'Введена несуществующая дата!'
		]
	];
}
