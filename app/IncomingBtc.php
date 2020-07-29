<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class IncomingBtc extends Model
{
    protected $fillable = [
        'transaction_id',
        'amount_btc',
        'usd_price',
        'released_date',
        'localbtc_released_date',
        'remaining',
        'was_used',
        'account_name',
        'hold',
        'hold_spend',
        'hold_remaining',
        'hold_by',
        'reserved_to',
        'wallet_only'
    ];

    protected $casts = [
        'hold_spend'  => 'array',
        'hold_by'     => 'array',
        'reserved_to' => 'array'
    ];

    public function transaction()
    {
        return $this->belongsTo('App\Transaction', 'transaction_id');
    }

    public function outgoings()
    {
        return $this->belongsToMany('App\OutgoingBtc', 'incoming_btc_outgoing_btc', 'incoming_id', 'outgoing_id');
    }

    /**
     * @param $accountName
     *
     * @param $date
     *
     * @param $contactID
     *
     * @return array
     */
    public static function totalRemaining($accountName, $date, $contactID): array
    {
        $incomingTransactions = self::where([
            'was_used'     => 0,
            'account_name' => $accountName
        ])
            ->orderBy('localbtc_released_date', 'asc')
            ->get();
        $totalRemaings        = 0;

        foreach ($incomingTransactions as $incomingTransaction) {
//            $incomingTransactionReleaseDate = new Carbon($incomingTransaction->localbtc_released_date);
//            $incomingTransactionReleaseDate = $incomingTransactionReleaseDate
//                ->setTimezone('EST')
//                ->format('Y-m-d H:i:s');
            $incomingTransactionReleaseDate = strtotime($incomingTransaction->localbtc_released_date);
            $endDate                        = strtotime($date);

            if ($incomingTransactionReleaseDate < $endDate) {
                $remainingAmount = $incomingTransaction->remaining;

                if ($contactID !== null && isset($incomingTransaction->hold_spend[$contactID])) {
                    $remainingAmount += $incomingTransaction->hold_spend[$contactID];
                }

                $totalRemaings += $remainingAmount;
            }
        }

        //$last_incoming = IncomingBtc::where('was_used', 0)->orderBy('id', 'desc')->first();
        //return ['total_remaining' => $totalRemaings, 'last_incoming' => $last_incoming->usd_price];

        return ['total_remaining' => $totalRemaings];
    }
}
