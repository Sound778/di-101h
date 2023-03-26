<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCommentsTable extends Migration
{
	/**
	 * Creates `comments` table
	 *
	 * @return void
	 */
	public function up()
	{
		$this->forge->addField('id');
		$this->forge->addField([
			'name' => [
				'type' => 'VARCHAR',
				'constraint' => 32
			],
			'text' => [
				'type' => 'TEXT'
			],
			'date' => [
				'type' => 'DATE'
			]
		]);

		$this->forge->createTable('comments');
	}

	/**
	 * Drops `comments` table
	 *
	 * @return void
	 */
	public function down()
	{
		$this->forge->dropTable('comments');
	}
}
