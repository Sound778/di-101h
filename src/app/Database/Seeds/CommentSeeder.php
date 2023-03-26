<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Faker\Factory;

class CommentSeeder extends Seeder
{
	/**
	 * Seeds data into `comments` table
	 *
	 * @return mixed|void
	 */
	public function run()
	{
		for ($i = 0; $i < 15; $i++) {
			$this->db->table('comments')->insert($this->makeFakeComment());
		}
	}

	/**
	 * Creates fake comment data for seeding it into `comments` table
	 *
	 * @return array
	 */
	private function makeFakeComment()
	{
		$faker = Factory::create();
		return [
			'name' => $faker->email,
			'text' => $faker->paragraph(3),
			'date' => $faker->date()
		];
	}
}
