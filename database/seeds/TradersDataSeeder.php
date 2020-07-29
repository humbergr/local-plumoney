<?php

use Illuminate\Database\Seeder;

class TradersDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('btc_bss_datas')->insert([
          'price' => 3500000
      ]);
      DB::table('usd_bss_datas')->insert([
          'price' => 810
      ]);
    }
}
