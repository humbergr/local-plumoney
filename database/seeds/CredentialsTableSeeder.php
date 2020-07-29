<?php

use Illuminate\Database\Seeder;

class CredentialsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('credentials')->insert([
          'username' => 'cristinadlr',
          'env_number' => 1,
          'is_active' => 1
      ]);

      DB::table('credentials')->insert([
          'username' => 'gdf12018',
          'env_number' => 2
      ]);
    }
}
