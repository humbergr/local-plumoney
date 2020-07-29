<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GenericPaymentsMethodsDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('generic_payments_methods')->insert([
            'title'    => 'Venmo',
            'metadata' => json_encode([
                'slug'   => 'venmo',
                'E-mail' => 'venmo@americantimeholding.com',
                'logo'   => '/img/landing/venmo.png'
            ])
        ]);

        DB::table('generic_payments_methods')->insert([
            'title'    => 'CashApp',
            'metadata' => json_encode([
                'slug'     => 'cashapp',
                '$CashTag' => '$Americankryptosbank',
                'logo'     => '/img/landing/cashapp.png'
            ])
        ]);

        DB::table('generic_payments_methods')->insert([
            'title'    => 'Payoneer',
            'metadata' => json_encode([
                'slug'   => 'payoneer',
                'E-mail' => 'americantimeholdings@gmail.com',
                'logo'   => '/img/landing/payoneer.png'
            ])
        ]);

        DB::table('generic_payments_methods')->insert([
            'title'    => 'Zelle',
            'metadata' => json_encode([
                'slug'     => 'zelle',
                'name'     => 'Guillermo',
                'lastname' => 'Scarpantonio',
                'E-mail'   => 'guillermo@scarpantonio.me',
                'logo'     => '/img/landing/zelle.png'
            ])
        ]);

        DB::table('generic_payments_methods')->insert([
            'title'    => 'PopMoney',
            'metadata' => json_encode([
                'slug'   => 'popmoney',
                'E-mail' => 'popmoney@americantimeholding.com',
                'logo'   => '/img/landing/popmoney.png'
            ])
        ]);

        DB::table('generic_payments_methods')->insert([
            'title'    => 'Pandco',
            'metadata' => json_encode([
                'slug'   => 'pandco',
                'E-mail' => '',
                'logo'   => '/img/landing/pandco.png'
            ])
        ]);
    }
}
