<?php

use Illuminate\Database\Seeder;

class CurrencyWalletsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('currency_wallets')->insert([
            'currency' => 'VES'
        ]);

        DB::table('currency_wallets')->insert([
            'currency' => 'USD'
        ]);
    }
}
