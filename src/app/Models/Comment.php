<?php

namespace App\Models;

use CodeIgniter\Model;

class Comment extends Model
{
	protected $table                = 'comments';
	protected $primaryKey           = 'id';
	protected $returnType           = 'array';
	protected $allowedFields        = ['name', 'text', 'date'];
}
