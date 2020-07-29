<?php

use Illuminate\Database\Seeder;

use Carbon\Carbon;

class PurchasesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $startDate    = new Carbon('2019-03-01T05:05:00+00:00');
        $startDate    = $startDate->format('Y-m-d H:i:s');
        $releasedDate = new Carbon('2019-03-01T05:05:00+00:00');
        $releasedDate = $releasedDate->setTimezone('EST')
            ->format('Y-m-d H:i:s');

        DB::table('transactions')->insert([
            'id'                     => '1',
            'bank_name'              => '',
            'transaction_id'         => '',
            'amount'                 => 0.19,
            'amount_btc'             => 0.00005000,
            'msg'                    => 'Initial Seed',
            'currency'               => 'USD',
            'json_data'              => '[]',
            'released_date'          => $releasedDate,
            'localbtc_released_date' => $startDate,
            'account_name'           => 'gdf12018',
            'type'                   => 'Outgoing',
            'is_manual'              => 1,
            'usd_price'              => 0.19,
            'error'                  => 0,
            'created_at'             => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at'             => Carbon::now()->format('Y-m-d H:i:s')
        ]);

        DB::table('incoming_btcs')->insert([
            'transaction_id'         => '1',
            'amount_btc'             => 0.00005000,
            'usd_price'              => 0.19,
            'released_date'          => $releasedDate,
            'localbtc_released_date' => $startDate,
            'account_name'           => 'gdf12018',
            'remaining'              => 0.00005000,
            'hold'                   => 0,
            'hold_spend'             => 0,
            'hold_remaining'         => 0,
            'hold_by'                => null,
            'reserved_to'            => null,
            'created_at'             => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at'             => Carbon::now()->format('Y-m-d H:i:s')
        ]);

        DB::table('transactions')->insert([
            'id'                     => '2',
            'bank_name'              => '',
            'transaction_id'         => '',
            'amount'                 => 1784.91,
            'amount_btc'             => 0.46953717,
            'msg'                    => 'Initial Seed',
            'currency'               => 'USD',
            'json_data'              => '[]',
            'released_date'          => $releasedDate,
            'localbtc_released_date' => $startDate,
            'account_name'           => 'cristinadlr',
            'type'                   => 'Outgoing',
            'is_manual'              => 1,
            'usd_price'              => 1784.91,
            'error'                  => 0,
            'created_at'             => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at'             => Carbon::now()->format('Y-m-d H:i:s')
        ]);

        DB::table('incoming_btcs')->insert([
            'transaction_id'         => '2',
            'amount_btc'             => 0.46953717,
            'usd_price'              => 1784.91,
            'released_date'          => $releasedDate,
            'localbtc_released_date' => $startDate,
            'account_name'           => 'cristinadlr',
            'remaining'              => 0.46953717,
            'hold'                   => 0,
            'hold_spend'             => 0,
            'hold_remaining'         => 0,
            'hold_by'                => null,
            'reserved_to'            => null,
            'created_at'             => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at'             => Carbon::now()->format('Y-m-d H:i:s')
        ]);
    }
}
