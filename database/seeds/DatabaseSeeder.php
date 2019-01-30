<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // 注意调用执行顺序不能出错

        $this->call(UsersTableSeeder::class);
        $this->call(TopicsTableSeeder::class);
		$this->call(ReplysTableSeeder::class);
        $this->call(LinksTableSeeder::class);

    }
}
