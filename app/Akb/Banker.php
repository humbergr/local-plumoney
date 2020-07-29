<?php


namespace App\Akb;


use App\CurrencyWallet;
use App\Http\Controllers\HelpersController;
use App\User;
use App\UserExchangeTransactions;
use App\MarketData;
use App\UserWalletsTransactions;
use App\WebsiteSettings;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use phpDocumentor\Reflection\Types\Integer;
use Pusher\Laravel\Facades\Pusher;
use stdClass;
use Stripe\Charge;

class Banker
{
    /**
     * Creates a Wallet when user doesn't have it and return wallet ID or return existing wallet ID.
     *
     * @param string $isoCurrency
     * @param null   $userID
     * @param bool   $returnWallet
     *
     * @return CurrencyWallet|int
     */
    public function createWallet($isoCurrency = 'USD', $userID = null, bool $returnWallet = false)
    {
        if ($userID === null) {
            $userID = Auth::user();
            $userID = $userID->id;
        }

        $userWallet = CurrencyWallet::where([
            'user_id'  => $userID,
            'currency' => $isoCurrency,
            'status'   => 1
        ])->first();

        if ($userWallet !== null) {
            return $userWallet->id;
        }

        $userWallet           = new CurrencyWallet();
        $userWallet->user_id  = $userID;
        $userWallet->currency = $isoCurrency;
        $userWallet->hash     = $userWallet->generateHash();
        $userWallet->status   = 1;
        $userWallet->save();

        return $returnWallet ? $userWallet->refresh() : $userWallet->id;
    }

    public function closeWallet()
    {

    }

    /**
     * @param int        $walletID
     * @param float      $amount
     * @param string     $currency
     * @param string     $approvedBy
     * @param int|null   $userID
     *
     * @param array|null $inputs
     *
     * @return mixed
     */
    public function rechargeWalletCredits(
        int $walletID,
        float $amount,
        string $currency,
        string $approvedBy,
        int $userID = null,
        array $inputs = null
    ) {
        $walletTransaction            = new UserWalletsTransactions();
        $walletTransaction->wallet_id = $walletID;
        $walletTransaction->user_id   = $userID ?? Auth::user()->id;
        $walletTransaction->type      = 1;
        $walletTransaction->status    = 0;
        $walletTransaction->amount    = $amount;
        $walletTransaction->currency  = $currency;

        if ($approvedBy === 'Stripe' || $approvedBy === 'QuickBook' || $approvedBy === 'SpecialPayment') {
            $walletTransaction->origin_foundation = 1;
            $walletTransaction->status            = 1;
            $walletTransaction->approved_at       = Carbon::now()->toDateTimeString();
            $walletTransaction->approved_by       = $approvedBy;
        }

        if ($inputs) {
            $exchangePriceData                       = self::walletsGetPrice($inputs);
            $walletTransaction->exchange_rate_id     = $exchangePriceData[2];
            $walletTransaction->tracking_id          = $inputs['tracking_id'];
            $walletTransaction->sender_fiat          = $inputs['sender'];
            $walletTransaction->sender_fiat_amount   = round(
                (float)str_replace(',', '', $inputs['to_send']),
                2
            );
            $walletTransaction->receiver_fiat        = $inputs['receiver'];
            $walletTransaction->receiver_fiat_amount = round(
                (float)str_replace(',', '', $inputs['to_receive']),
                2
            );
            $walletTransaction->exchange_rate        = round($exchangePriceData[0], 2);
            $walletTransaction->fee_at_moment        = $exchangePriceData[3];
            $walletTransaction->payment_way          = $approvedBy;
            $walletTransaction->payment_source       = $inputs['pay_method'];
            $walletTransaction->exchange_related     = $inputs['exchange_related'] ?? 0;
        }

        $walletTransaction->save();

        return $walletTransaction->id;
    }

    /**
     * @param int         $walletID
     * @param float       $amount
     * @param string      $currency
     * @param int         $purpose
     * @param string      $relatedEmail
     * @param int|null    $originWalletId
     * @param int|null    $userID
     * @param string|null $description
     *
     * @return mixed
     */
    public function transferWalletCredits(
        int $walletID,
        float $amount,
        string $currency,
        int $purpose,
        string $relatedEmail,
        int $originWalletId = null,
        int $userID = null,
        string $description = null
    ) {
        $walletTransaction              = new UserWalletsTransactions();
        $walletTransaction->wallet_id   = $walletID;
        $walletTransaction->user_id     = $userID ?? Auth::user()->id;
        $walletTransaction->type        = 4;
        $walletTransaction->status      = 1;
        $walletTransaction->amount      = $amount;
        $walletTransaction->currency    = $currency;
        $walletTransaction->purpose     = $purpose;
        $walletTransaction->tracking_id = uniqid('wtr-', false);

        if ($purpose === 1) {
            $walletTransaction->origin_foundation = 8;
        } else {
            $walletTransaction->withdraw_mode = 6;
        }

        $metadataArr = [
            'related_email' => $relatedEmail
        ];

        if ($description) {
            $metadataArr['description'] = $description;
        }

        $walletTransaction->metadata    = $metadataArr;
        $walletTransaction->approved_at = Carbon::now()->toDateTimeString();
        $walletTransaction->approved_by = $originWalletId ?? 'Automatic Withdraw';

        $walletTransaction->save();

        return $walletTransaction->id;
    }

    /**
     * @param int        $walletID
     * @param float      $amount
     * @param string     $currency
     * @param string     $paymentMethod
     * @param int|null   $relatedWalletTransaction
     *
     * @param int        $purpose
     * @param string     $approvedBy
     * @param int|null   $userID
     *
     * @param array|null $inputs
     *
     * @param bool       $returnModel
     *
     * @return mixed
     */
    public function holdWalletCredits(
        int $walletID,
        float $amount,
        string $currency,
        string $paymentMethod = null,
        int $relatedWalletTransaction = null,
        int $purpose = 1,
        string $approvedBy = null,
        int $userID = null,
        array $inputs = null,
        bool $returnModel = false
    ) {
        $walletTransaction                                = new UserWalletsTransactions();
        $walletTransaction->wallet_id                     = $walletID;
        $walletTransaction->user_id                       = $userID ?? Auth::user()->id;
        $walletTransaction->type                          = 3;
        $walletTransaction->status                        = 1;
        $walletTransaction->amount                        = $amount;
        $walletTransaction->currency                      = $currency;
        $walletTransaction->approved_at                   = Carbon::now()->toDateTimeString();
        $walletTransaction->approved_by                   = $approvedBy ?? 'Sending money transaction';
        $walletTransaction->wallet_transaction_related_id = $relatedWalletTransaction;
        $walletTransaction->purpose                       = $purpose;

        if ($paymentMethod) {
            if ($paymentMethod === 'zelle') {
                $originFoundation = 2;
            }
            if ($paymentMethod === 'venmo') {
                $originFoundation = 3;
            }
            if ($paymentMethod === 'cashapp') {
                $originFoundation = 4;
            }
            if ($paymentMethod === 'payoneer') {
                $originFoundation = 5;
            }
            if ($paymentMethod === 'popmoney') {
                $originFoundation = 6;
            }
            if ($paymentMethod === 'pandco') {
                $originFoundation = 7;
            }
            if ($paymentMethod === 'userWallet') {
                $originFoundation = 8;
            }
            if ($paymentMethod === 'cash') {
                $originFoundation = 9;
            }
            if ($paymentMethod === 'amaz_prepaid') {
                $originFoundation = 10;
            }
            if ($paymentMethod === 'ath_prepaid') {
                $originFoundation = 11;
            }
            $walletTransaction->origin_foundation = $originFoundation;
        }

        if ($inputs) {
            $exchangePriceData                       = self::walletsGetPrice($inputs);
            $walletTransaction->exchange_rate_id     = $exchangePriceData[2];
            $walletTransaction->tracking_id          = $inputs['tracking_id'];
            $walletTransaction->sender_fiat          = $inputs['sender'];
            $walletTransaction->sender_fiat_amount   = round(
                (float)str_replace(',', '', $inputs['to_send']),
                2
            );
            $walletTransaction->receiver_fiat        = $inputs['receiver'];
            $walletTransaction->receiver_fiat_amount = round(
                (float)str_replace(',', '', $inputs['to_receive']),
                2
            );
            $walletTransaction->exchange_rate        = round($exchangePriceData[0], 2);
            $walletTransaction->fee_at_moment        = $exchangePriceData[3];
            $method                                  = (int)env('QBPAY') !== 1 ? 'Stripe' : 'QuickBook';
            $walletTransaction->payment_way          = $paymentMethod ?? $method;
            $walletTransaction->payment_source       = $inputs['pay_method'];
            $walletTransaction->exchange_related     = $inputs['exchange_related'] ?? 0;
        }

        $walletTransaction->save();

        return $returnModel ? $walletTransaction : $walletTransaction->id;
    }

    /**
     * @param UserWalletsTransactions $transaction
     * @param int                     $purpose
     *
     * @return mixed
     */
    public function holdWalletCreditsFromTransaction(
        UserWalletsTransactions $transaction,
        int $purpose = 1
    ) {
        $walletTransaction                                = new UserWalletsTransactions();
        $walletTransaction->wallet_id                     = $transaction->wallet_id;
        $walletTransaction->user_id                       = $transaction->user_id;
        $walletTransaction->status                        = 1;
        $walletTransaction->amount                        = $transaction->amount;
        $walletTransaction->currency                      = $transaction->currency;
        $walletTransaction->approved_at                   = Carbon::now()->toDateTimeString();
        $walletTransaction->approved_by                   = 'Sending money transaction';
        $walletTransaction->wallet_transaction_related_id = $transaction->id;
        $walletTransaction->purpose                       = $purpose;
        $walletTransaction->type                          = 3;
        $walletTransaction->exchange_rate_id              = $transaction->exchange_rate_id ?? null;
        $walletTransaction->tracking_id                   = $transaction->tracking_id ?? null;
        $walletTransaction->sender_fiat                   = $transaction->sender_fiat ?? null;
        $walletTransaction->sender_fiat_amount            = $transaction->sender_fiat_amount ?? null;
        $walletTransaction->receiver_fiat                 = $transaction->receiver_fiat ?? null;
        $walletTransaction->receiver_fiat_amount          = $transaction->receiver_fiat_amount ?? null;
        $walletTransaction->exchange_rate                 = $transaction->exchange_rate ?? null;
        $walletTransaction->fee_at_moment                 = $transaction->fee_at_moment ?? null;
        $walletTransaction->payment_way                   = $transaction->payment_way ?? null;
        $walletTransaction->payment_source                = $transaction->payment_source ?? null;
        $walletTransaction->exchange_related              = $transaction->exchange_related ?? null;

        $walletTransaction->save();

        return $walletTransaction->id;
    }

    /**
     * @param UserWalletsTransactions $holdTransaction
     *
     * @return int
     */
    public function rechargeWalletFromHold(UserWalletsTransactions $holdTransaction): int
    {
        $walletTransaction              = new UserWalletsTransactions();
        $walletTransaction->wallet_id   = $holdTransaction->wallet_id;
        $walletTransaction->user_id     = $holdTransaction->user_id;
        $walletTransaction->status      = 1;
        $walletTransaction->amount      = $holdTransaction->amount;
        $walletTransaction->currency    = $holdTransaction->currency;
        $walletTransaction->approved_at = Carbon::now()->toDateTimeString();
        $walletTransaction->approved_by = 'Trader Master: ' . Auth::user()->name;

        if ($holdTransaction->purpose === 1) {
            $walletTransaction->type              = 1;
            $walletTransaction->origin_foundation = $holdTransaction->origin_foundation;
        } else {
            $walletTransaction->type          = 2;
            $walletTransaction->withdraw_mode = 1;
        }

        $walletTransaction->exchange_rate_id     = $holdTransaction->exchange_rate_id ?? null;
        $walletTransaction->tracking_id          = $holdTransaction->tracking_id ?? null;
        $walletTransaction->sender_fiat          = $holdTransaction->sender_fiat ?? null;
        $walletTransaction->sender_fiat_amount   = $holdTransaction->sender_fiat_amount ?? null;
        $walletTransaction->receiver_fiat        = $holdTransaction->receiver_fiat ?? null;
        $walletTransaction->receiver_fiat_amount = $holdTransaction->receiver_fiat_amount ?? null;
        $walletTransaction->exchange_rate        = $holdTransaction->exchange_rate ?? null;
        $walletTransaction->fee_at_moment        = $holdTransaction->fee_at_moment ?? null;
        $walletTransaction->payment_way          = $holdTransaction->payment_way ?? null;
        $walletTransaction->payment_source       = $holdTransaction->payment_source ?? null;
        $walletTransaction->exchange_related     = $holdTransaction->exchange_related ?? null;

        $walletTransaction->save();

        return $walletTransaction->id;
    }

    /**
     * @param UserWalletsTransactions $holdTransaction
     * @param int                     $status
     * @param bool                    $relatedTransactionID
     *
     * @return mixed
     */
    public function removeHold(
        UserWalletsTransactions $holdTransaction,
        int $status,
        bool $relatedTransactionID = false
    ) {
        $holdTransaction->status = $status;

        if ($status === 3) {
            $holdTransaction->rejected_at = Carbon::now();
            $holdTransaction->rejected_by = Auth::user()->name;
        }

        $holdTransaction->save();

        if ($relatedTransactionID) {
            return $holdTransaction->wallet_transaction_related_id;
        }

        return true;
    }

    /**
     * @param UserWalletsTransactions $holdTransaction
     *
     * @return integer
     */
    public function withdrawWalletFromHold(UserWalletsTransactions $holdTransaction): int
    {
        $walletTransaction                       = new UserWalletsTransactions();
        $walletTransaction->wallet_id            = $holdTransaction->wallet_id;
        $walletTransaction->user_id              = $holdTransaction->user_id;
        $walletTransaction->type                 = 2;
        $walletTransaction->status               = 1;
        $walletTransaction->amount               = $holdTransaction->amount;
        $walletTransaction->currency             = $holdTransaction->currency;
        $walletTransaction->approved_at          = Carbon::now()->toDateTimeString();
        $walletTransaction->approved_by          = 'Trader Master: ' . Auth::user()->name;
        $walletTransaction->withdraw_mode        = 1;
        $walletTransaction->exchange_rate_id     = $holdTransaction->exchange_rate_id ?? null;
        $walletTransaction->tracking_id          = $holdTransaction->tracking_id ?? null;
        $walletTransaction->sender_fiat          = $holdTransaction->sender_fiat ?? null;
        $walletTransaction->sender_fiat_amount   = $holdTransaction->sender_fiat_amount ?? null;
        $walletTransaction->receiver_fiat        = $holdTransaction->receiver_fiat ?? null;
        $walletTransaction->receiver_fiat_amount = $holdTransaction->receiver_fiat_amount ?? null;
        $walletTransaction->exchange_rate        = $holdTransaction->exchange_rate ?? null;
        $walletTransaction->fee_at_moment        = $holdTransaction->fee_at_moment ?? null;
        $walletTransaction->payment_way          = $holdTransaction->payment_way ?? null;
        $walletTransaction->payment_source       = $holdTransaction->payment_source ?? null;
        $walletTransaction->exchange_related     = $holdTransaction->exchange_related ?? null;

        $walletTransaction->save();

        return $walletTransaction->id;
    }

    /**
     * @param array       $walletOrigin
     * @param int         $idWalletDestiny
     * @param float       $amount
     * @param int         $destinyUserID
     * @param string      $destinyEmail
     * @param string      $originEmail
     * @param string|null $description
     *
     * @return mixed|null
     */
    public function transferBetweenWallets(
        array $walletOrigin,
        int $idWalletDestiny,
        float $amount,
        int $destinyUserID,
        string $destinyEmail,
        string $originEmail,
        string $description = null
    ) {
        if ($this->verifyOutgoingAmount($walletOrigin, $amount)) {
            //1st Create a withdraw for the transfer.
            $sendOpID = $this->transferWalletCredits(
                $walletOrigin['id'],
                $amount,
                $walletOrigin['currency'],
                2,
                $destinyEmail,
                null,
                null,
                $description
            );
            //2nd Create Recharge on destination wallet.
            $this->transferWalletCredits(
                $idWalletDestiny,
                $amount,
                $walletOrigin['currency'],
                1,
                $originEmail,
                $walletOrigin['id'],
                $destinyUserID,
                $description
            );

            return $sendOpID;
        }

        return null;
    }

    /**
     * @param array $walletOrigin
     * @param float $amount
     *
     * @return bool
     */
    public function verifyOutgoingAmount(array $walletOrigin, float $amount): bool
    {
        return (string)$walletOrigin['numbers']['available'] >= (string)$amount;
    }

    /**
     * @param int         $holdID
     * @param null|string $paymentMethod
     * @param Charge|null $charge
     * @param array|null  $qbPay
     *
     * @return bool
     */
    public function payWalletTransaction(
        int $holdID,
        ?string $paymentMethod,
        Charge $charge = null,
        array $qbPay = null
    ): bool {
        /** @var UserWalletsTransactions $walletTransaction */
        $walletTransaction             = UserWalletsTransactions::find($holdID);
        $walletTransaction->is_revised = 0;
        $walletTransaction->is_payed   = 1;
        $walletTransaction->payed_at   = Carbon::now()->toDateTimeString();

        if ($charge) {
            $walletTransaction->payed_by    = 'Stripe: ' . $charge->id;
            $walletTransaction->stripe_data = $charge;
        } elseif ($qbPay) {
            $walletTransaction->payed_by    = 'QuickBook: ' . $qbPay['id'];
            $walletTransaction->stripe_data = json_encode($qbPay);
        } else {
            $walletTransaction->payed_by    = $paymentMethod;
            $walletTransaction->stripe_data = null;
        }

        return $walletTransaction->save();
    }

    /**
     * @param int         $holdID
     * @param string|null $message
     *
     * @return bool
     */
    public function rejectWalletTransaction(int $holdID, string $message = null): bool
    {
        /** @var UserWalletsTransactions $walletTransaction */
        $walletTransaction             = UserWalletsTransactions::find($holdID);
        $walletTransaction->status     = 3;
        $walletTransaction->is_revised = 1;
        $walletTransaction->failed_at  = Carbon::now()->toDateTimeString();
        $walletTransaction->failed_by  = $message;

        return $walletTransaction->save();
    }

    /**
     * @param string $senderCurrency
     * @param string $senderCountry
     * @param string $receiverCurrency
     * @param string $receiverCountry
     *
     * @return array
     */
    public static function getExchangeRate(
        string $senderCurrency,
        string $senderCountry,
        string $receiverCurrency,
        string $receiverCountry
    ): array {
        $localExchange = DB::connection('mysql2')
            ->select(
                'select * from anchor_exchange_rates where sender = :sender ' .
                'and sender_country = :senderCountry ' .
                'and receiver = :receiver ' .
                'and receiver_country = :receiverCountry ' .
                'order by ID DESC limit 1',
                [
                    'sender'          => $senderCurrency,
                    'senderCountry'   => $senderCountry,
                    'receiver'        => $receiverCurrency,
                    'receiverCountry' => $receiverCountry
                ]
            );

        if (!empty($localExchange)) {
            return $localExchange;
        }

        $exchangeRates = HelpersController::getExchangeRates()['rates'][$senderCurrency];
        $usdToDest     = DB::connection('mysql2')
            ->select(
                'select * from anchor_exchange_rates where sender = :sender ' .
                'and sender_country = :senderCountry ' .
                'and receiver = :receiver ' .
                'and receiver_country = :receiverCountry ' .
                'order by ID DESC limit 1',
                [
                    'sender'          => 'USD',
                    'senderCountry'   => 'United States',
                    'receiver'        => $receiverCurrency,
                    'receiverCountry' => $receiverCountry
                ]
            );
        if (isset($usdToDest[0])) {
            $usdToDest = $usdToDest[0];
        } else {
            $usdToDest                = new stdClass();
            $usdToDest->exchange_rate = 1;
            $usdToDest->id            = null;
        }
        $price                   = (1 / $exchangeRates) * $usdToDest->exchange_rate;
        $response                = new stdClass();
        $response->exchange_rate = $price;
        $response->id            = $usdToDest->id;
        $response->sender_rate   = $exchangeRates;
        $response->no_sys        = true;

        return [$response];
    }

    private static $countries = [
        'Venezuela'   => [
            'currency' => 'ves',
            'iso'      => 'ven'
        ],
        'Colombia'    => [
            'currency' => 'cop',
            'iso'      => 'col'
        ],
        'Panamá'      => [
            'currency' => 'pab',
            'iso'      => 'pan'
        ],
        'Perú'        => [
            'currency' => 'pen',
            'iso'      => 'per'
        ],
        'Chile'       => [
            'currency' => 'CLP',
            'iso'      => 'chl'
        ],
        'Argentina'   => [
            'currency' => 'ARS',
            'iso'      => 'arg'
        ],
        'Reino Unido' => [
            'currency' => 'GBP',
            'iso'      => 'gbr'
        ],
        'España'      => [
            'currency' => 'EUR',
            'iso'      => 'esp'
        ],
        'Portugal'    => [
            'currency' => 'EUR',
            'iso'      => 'prt'
        ],
        'Italia'      => [
            'currency' => 'EUR',
            'iso'      => 'ita'
        ],
        'Francia'     => [
            'currency' => 'EUR',
            'iso'      => 'fra'
        ],
        'Alemania'    => [
            'currency' => 'EUR',
            'iso'      => 'deu'
        ],
        'México'      => [
            'currency' => 'MXN',
            'iso'      => 'mex'
        ],
        'Brazil'      => [
            'currency' => 'BRL',
            'iso'      => 'bra'
        ]
    ];

    /**
     * @param int  $exchangeRateID
     * @param null $utility
     *
     * @param null $feeAtMoment
     *
     * @return array|null
     */
    public static function getExchangeRateByID(
        int $exchangeRateID,
        $utility = null,
        $feeAtMoment = null
    ) {
        $relationRateArray = DB::connection('mysql2')->select(
            'select * from anchor_exchange_rates ' .
            ' where id = :exchangeRateID ',
            [
                'exchangeRateID' => $exchangeRateID
            ]
        )[0];

        if ($relationRateArray->receiver !== 'USD') {
            $senderCountry = self::$countries[$relationRateArray->receiver_country];
            $tableName     = strtolower($senderCountry['currency']) . '_' . strtolower($senderCountry['iso']) . '_usd_prices';

            $countryRateArray = DB::connection('mysql2')->select(
                'select * from ' . $tableName . ' ' .
                ' where id = :rateID ',
                [
                    'rateID' => $relationRateArray->receiver_rate_id
                ]
            )[0];


            $sellPrice = $countryRateArray->sell_price;
            if ($relationRateArray->receiver === 'VES2') {
                $sellPrice = $countryRateArray->sell_price_2;
            }

            if ($utility) {
                if ($feeAtMoment) {
                    $rateUtility = $feeAtMoment;
                } else {
                    $rateUtility = env('FEE_GENERAL'); //TODO Dynamic
                    if ($relationRateArray->receiver === 'VES2' || $relationRateArray->receiver === 'VES') {
                        $rateUtility = env('FEE_TO_VEN');
                    }
                }

                if ($sellPrice < 1) {
                    $calculatedRate = $sellPrice + (
                            ($sellPrice * $rateUtility) / 100
                        );
                } else {
                    $calculatedRate = $sellPrice - (
                            ($sellPrice * $rateUtility) / 100
                        );
                }
              ///  dd($relationRateArray, $countryRateArray);
                return [$relationRateArray, $countryRateArray, $calculatedRate];
            }
         //   dd($relationRateArray, $countryRateArray);
            return [$relationRateArray, $countryRateArray];
           
        }

        $senderCountry = self::$countries[$relationRateArray->sender_country];
        $tableName     = strtolower($senderCountry['currency']) . '_' . strtolower($senderCountry['iso']) . '_usd_prices';

        $countryRateArray = DB::connection('mysql2')->select(
            'select * from ' . $tableName . ' ' .
            ' where id = :rateID ',
            [
                'rateID' => $relationRateArray->receiver_rate_id
            ]
        )[0];

        $buyPrice = $countryRateArray->buy_price;
        if ($relationRateArray->sender === 'VES2') {
            $buyPrice = $countryRateArray->buy_price_2;
        }

        if ($utility) {
            if ($feeAtMoment) {
                $rateUtility = $feeAtMoment;
            } else {
                $rateUtility = env('FEE_GENERAL'); //TODO Dynamic
                if ($relationRateArray->sender === 'VES2' || $relationRateArray->sender === 'VES') {
                    $rateUtility = env('FEE_FROM_VEN');
                }
            }

            if ($buyPrice < 1) {
                $calculatedRate = $buyPrice - (
                        ($buyPrice * $rateUtility) / 100
                    );
            } else {
                $calculatedRate = $buyPrice + (
                        ($buyPrice * $rateUtility) / 100
                    );
            }

//            dd($relationRateArray, $countryRateArray, $calculatedRate);

            return [$relationRateArray, $countryRateArray, $calculatedRate];
        }

        return [$relationRateArray, $countryRateArray];
        // return;
    }

    /**
     * @param int  $exchangeRateID
     * @param null $utility
     *
     * @param null $feeAtMoment
     *
     * @return array|null
     */
    public static function getWalletsExchangeRateByID(
        int $exchangeRateID,
        $utility = null,
        $feeAtMoment = null
    ) {
        $relationRateArray = DB::connection('mysql2')->select(
            'select * from anchor_exchange_rates ' .
            ' where id = :exchangeRateID ',
            [
                'exchangeRateID' => $exchangeRateID
            ]
        )[0];

        if ($relationRateArray->receiver !== 'USD') {
            $senderCountry = self::$countries[$relationRateArray->receiver_country];
            $tableName     = strtolower($senderCountry['currency']) . '_' . strtolower($senderCountry['iso']) . '_usd_prices';

            $countryRateArray = DB::connection('mysql2')->select(
                'select * from ' . $tableName . ' ' .
                ' where id = :rateID ',
                [
                    'rateID' => $relationRateArray->receiver_rate_id
                ]
            )[0];

            $sellPrice = $countryRateArray->sell_price;
            if ($relationRateArray->receiver === 'VES2') {
                $sellPrice = $countryRateArray->sell_price_2;
            }

            if ($utility) {
                if ($feeAtMoment) {
                    $rateUtility = $feeAtMoment;
                } else {
                    $rateUtility = env('WALLETS_FEE_GENERAL'); //TODO Dynamic
                    if ($relationRateArray->receiver === 'VES2' || $relationRateArray->receiver === 'VES') {
                        $rateUtility = env('WALLETS_FEE_TO_VEN');
                    }
                }
                $calculatedRate = $sellPrice - (
                        ($sellPrice * $rateUtility) / 100
                    );

                return [$relationRateArray, $countryRateArray, $calculatedRate];
            }

            return [$relationRateArray, $countryRateArray];
        }

        $senderCountry = self::$countries[$relationRateArray->sender_country];
        $tableName     = strtolower($senderCountry['currency']) . '_' . strtolower($senderCountry['iso']) . '_usd_prices';

        $countryRateArray = DB::connection('mysql2')->select(
            'select * from ' . $tableName . ' ' .
            ' where id = :rateID ',
            [
                'rateID' => $relationRateArray->receiver_rate_id
            ]
        )[0];

        $buyPrice = $countryRateArray->buy_price;
        if ($relationRateArray->sender === 'VES2') {
            $buyPrice = $countryRateArray->buy_price_2;
        }

        if ($utility) {
            if ($feeAtMoment) {
                $rateUtility = $feeAtMoment;
            } else {
                $rateUtility = env('WALLETS_FEE_GENERAL'); //TODO Dynamic
                if ($relationRateArray->sender === 'VES2' || $relationRateArray->sender === 'VES') {
                    $rateUtility = env('WALLETS_FEE_FROM_VEN');
                }
            }
            $calculatedRate = $buyPrice - (
                    ($buyPrice * $rateUtility) / 100
                );

            return [$relationRateArray, $countryRateArray, $calculatedRate];
        }

        return [$relationRateArray, $countryRateArray];
    }

    public static $generalCountries = [
        'Sur América'      => [
            [
                'Venezuela',
                've',
                'ven',
                'VES',
                '+58'
            ],
            [
                'Colombia',
                'co',
                'col',
                'COP',
                '+57'
            ],
            [
                'Perú',
                'pe',
                'per',
                'PEN',
                '+51'
            ],
            [
                'Chile',
                'cl',
                'chl',
                'CLP',
                '+56'
            ],
            [
                'Argentina',
                'ar',
                'arg',
                'ARS',
                '+54'
            ],
            [
                'Brazil',
                'br',
                'bra',
                'BRL',
                '+55'
            ],
            [
                'Ecuador',
                'ec',
                'ecu',
                'USD',
                '+593'
            ],
            [
                'Bolivia',
                'bo',
                'bol',
                'BOB',
                '+591'
            ],
            [
                'Paraguay',
                'py',
                'pry',
                'PYG',
                '+595'
            ],
            [
                'Uruguay',
                'uy',
                'ury',
                'UYU',
                '+598'
            ],
        ],
        'Centro América'   => [
            [
                'Panamá',
                'pa',
                'pan',
                'PAB',
                '+507'
            ],
            [
                'República Dominicana',
                'do',
                'dom',
                'DOP',
                '+1-809'
            ],
            [
                'Guatemala',
                'gt',
                'gtm',
                'GTQ',
                '+502'
            ],
            [
                'El Salvador',
                'sv',
                'svl',
                'USD',
                '+503'
            ],
            [
                'Honduras',
                'hn',
                'hnd',
                'HNL',
                '+504'
            ],
            [
                'Nicaragua',
                'ni',
                'nic',
                'NIO',
                '+505'
            ],
            [
                'Costa Rica',
                'cr',
                'cri',
                'CRC',
                '+506'
            ],
            [
                'Belize',
                'bz',
                'blz',
                'BZD',
                '+501'
            ]
        ],
        'Norte América'    => [
            [
                'México',
                'mx',
                'mex',
                'MXN',
                '+52'
            ],
            [
                'United States',
                'us',
                'usa',
                'USD',
                '+1'
            ],
            [
                'Canada',
                'ca',
                'can',
                'CAD',
                '+1'
            ],
        ],
        'Islas del Caribe' => [
            [
                'Puerto Rico',
                'pr',
                'pri',
                'USD',
                '+1-787'
            ],
            [
                'Aruba',
                'aw',
                'abw',
                'AWG',
                '+297'
            ],
            [
                'Curacao',
                'cw',
                'cuw',
                'ANG',
                '+599'
            ],
            [
                'Trinidad y Tobago',
                'tt',
                'tto',
                'TTD',
                '+1-868'
            ],
            [
                'Bahamas',
                'bs',
                'bhs',
                'BSD',
                '+1-242'
            ],
            [
                'Barbados',
                'bb',
                'brb',
                'BBD',
                '+1-246'
            ]
        ],
        'Europa'           => [
            [
                'Reino Unido',
                'gb',
                'gbr',
                'GBP',
                '+44'
            ],
            [
                'España',
                'es',
                'esp',
                'EUR',
                '+34'
            ],
            [
                'Portugal',
                'pt',
                'prt',
                'EUR',
                '+351'
            ],
            [
                'Italia',
                'it',
                'ita',
                'EUR',
                '+39'
            ],
            [
                'Francia',
                'fr',
                'fra',
                'EUR',
                '+33'
            ],
            [
                'Alemania',
                'de',
                'deu',
                'EUR',
                '+49'
            ],
        ],
    ];

    /**
     * @param bool|null $simple //Country name by Money ISO
     *
     * @return array
     */
    public static function getOpCountries(bool $simple = null): array
    {
        $countries      = DB::connection('mysql2')->select(
            'select * from countries '
        );
        $countriesArray = [];

        foreach ($countries as $country) {
            $countriesArray[$country->name] = $country;
        }
        unset($countries);

        $generalCountries = self::$generalCountries;

        if ($simple) {
            $simpleCountriesArr = [];
            foreach ($generalCountries as $key => $region) {
                foreach ($region as $rKey => $country) {
                    $simpleCountriesArr[$country[3]] = $country[0];
                }
            }

            return $simpleCountriesArr;
        }

        foreach ($generalCountries as $key => $region) {
            foreach ($region as $rKey => $country) {
                if (!isset($countriesArray[$country[0]])) {
                    $country['noLB'] = true;
                }
                $region[$rKey] = $country;
            }
            $generalCountries[$key] = $region;
        }

        return $generalCountries;
    }

    /**
     * @param array $inputs
     *
     * @return array
     */ 
    public static function getPrice(array $inputs): array
    {
        $usdEquivalent = $inputs['amount'] ?? 0;
        $akbFee        = $inputs['ajax_fee_percent'] ?? env('FEE_GENERAL');

        //1st Verify if sender is VES
        if ($inputs['sender'] === 'VES') {
            $akbFee                = $inputs['ajax_fee_percent'] ?? env('FEE_FROM_VEN');
            $exchangeRateArrayVES  = self::getExchangeRate(
                'VES',
                'Venezuela',
                'USD',
                'United States'
            )[0];
            $exchangeRateArrayVES2 = self::getExchangeRate(
                'VES2',
                'Venezuela',
                'USD',
                'United States'
            )[0];
            $exchangeRateArrayPr   = ($exchangeRateArrayVES->exchange_rate + $exchangeRateArrayVES2->exchange_rate) / 2;
            $vesEquivalent         = null;
            $rangeVES              = json_decode($exchangeRateArrayVES->range, true);
            $receiving             = null;

            if (isset($inputs['receiving']) && (float)$inputs['receiving'] > 0) {
                if ($inputs['receiver'] === 'USD') {
                    $receiving = (float)$inputs['receiving'];
                } else {
                    $exchangeRateArray = self::getExchangeRate(
                        'USD',
                        'United States',
                        $inputs['receiver'],
                        $inputs['sender_country']
                    )[0];
                    $receiving         = (float)$inputs['receiving'] / $exchangeRateArray->exchange_rate;
                }
            } else {
                $vesEquivalent = $inputs['amount'] * $exchangeRateArrayPr;
            }

            if (($receiving !== null && $receiving > $rangeVES[0] && $receiving < $rangeVES[1]) ||
                ($vesEquivalent !== null && ($vesEquivalent < 15 || ($vesEquivalent > $rangeVES[0] && $vesEquivalent < $rangeVES[1])))) {
                if ($inputs['receiver'] !== 'USD') {
                    $exchangeRateArrayVES = self::getExchangeRate(
                        'VES',
                        'Venezuela',
                        $inputs['receiver'],
                        $inputs['receiver_country']
                    )[0];
                }

                $priceToReturn  = $exchangeRateArrayVES->exchange_rate;
                $exchangeRateID = $exchangeRateArrayVES->id;
                $usdEquivalent  = $inputs['amount'] * $exchangeRateArrayVES->exchange_rate;
            } else {
                if ($inputs['receiver'] !== 'USD') {
                    $exchangeRateArrayVES2 = self::getExchangeRate(
                        'VES2',
                        'Venezuela',
                        $inputs['receiver'],
                        $inputs['receiver_country']
                    )[0];
                }

                $priceToReturn  = $exchangeRateArrayVES2->exchange_rate;
                $exchangeRateID = $exchangeRateArrayVES2->id;
                $usdEquivalent  = $inputs['amount'] * $exchangeRateArrayVES2->exchange_rate;
            }
        } elseif ($inputs['receiver'] === 'VES') {
            $akbFee                = $inputs['ajax_fee_percent'] ?? env('FEE_TO_VEN');
            $exchangeRateArrayVES  = self::getExchangeRate(
                'USD',
                'United States',
                'VES',
                'Venezuela'
            )[0];
            $exchangeRateArrayVES2 = self::getExchangeRate(
                'USD',
                'United States',
                'VES2',
                'Venezuela'
            )[0];
            $vesEquivalent         = $inputs['amount'];

            if ($inputs['sender'] !== 'USD') {
                $exchangeRateArray = self::getExchangeRate(
                    $inputs['sender'],
                    $inputs['sender_country'],
                    'VES',
                    'Venezuela'
                )[0];

                if (!isset($exchangeRateArray->no_sys)) {
                    $exchangeRateArray = self::getExchangeRate(
                        $inputs['sender'],
                        $inputs['sender_country'],
                        'USD',
                        'United States'
                    )[0];
                    $vesEquivalent     = $inputs['amount'] * $exchangeRateArray->exchange_rate;
                } else {
                    $vesEquivalent = $inputs['amount'] / $exchangeRateArray->sender_rate;
                }

                $usdEquivalent = $vesEquivalent;
            }
            $rangeVES = json_decode($exchangeRateArrayVES->range, true);

            if ($vesEquivalent < 15 || ($vesEquivalent > $rangeVES[0] && $vesEquivalent < $rangeVES[1])) {
                if ($inputs['sender'] !== 'USD') {
                    $exchangeRateArrayVES = self::getExchangeRate(
                        $inputs['sender'],
                        $inputs['sender_country'],
                        'VES',
                        'Venezuela'
                    )[0];
                }

                $priceToReturn  = $exchangeRateArrayVES->exchange_rate;
                $exchangeRateID = $exchangeRateArrayVES->id;
            } else {
                if ($inputs['sender'] !== 'USD') {
                    $exchangeRateArrayVES2 = self::getExchangeRate(
                        $inputs['sender'],
                        $inputs['sender_country'],
                        'VES2',
                        'Venezuela'
                    )[0];
                }

                $priceToReturn  = $exchangeRateArrayVES2->exchange_rate;
                $exchangeRateID = $exchangeRateArrayVES2->id;
            }
        } else {
            $exchangeRateArray = self::getExchangeRate(
                $inputs['sender'],
                $inputs['sender_country'],
                $inputs['receiver'],
                $inputs['receiver_country']
            )[0];
            $exchangeRateID    = $exchangeRateArray->id;
            $priceToReturn     = $exchangeRateArray->exchange_rate;

            if ($inputs['sender'] !== 'USD') {
                if (!isset($exchangeRateArray->no_sys)) {
                    $exchangeRateArray = self::getExchangeRate(
                        $inputs['sender'],
                        $inputs['sender_country'],
                        'USD',
                        'United States'
                    )[0];
                }
                $usdEquivalent = $inputs['amount'] / $exchangeRateArray->exchange_rate;
            }
        }

        $fee           = ($priceToReturn * $akbFee) / 100;
        $originalPrice = $priceToReturn;
        $priceToReturn -= $fee;

        return [$priceToReturn, $usdEquivalent, $exchangeRateID, $akbFee, 'original' => $originalPrice];
    }

    /**
     * @param array       $inputs
     * @param string|null $intention
     *
     * @return array
     */
    public static function walletsGetPrice(array $inputs, string $intention = 'charge'): array
    {
        $usdEquivalent = $inputs['amount'] ?? 0;
        $envFee        = $intention === 'withdraw' ? env('WALLETS_FEE_GENERAL_WITHDRAW')
            : env('WALLETS_FEE_GENERAL_CHARGE');
        $akbFee        = $inputs['ajax_fee_percent'] ?? $envFee;

        //1st Verify if sender is VES
        if ($inputs['sender'] === 'VES') {
            $akbFee                = $inputs['ajax_fee_percent'] ?? env('WALLETS_FEE_FROM_VEN');
            $exchangeRateArrayVES  = self::getExchangeRate(
                'VES',
                'Venezuela',
                'USD',
                'United States'
            )[0];
            $exchangeRateArrayVES2 = self::getExchangeRate(
                'VES2',
                'Venezuela',
                'USD',
                'United States'
            )[0];
            $exchangeRateArrayPr   = ($exchangeRateArrayVES->exchange_rate + $exchangeRateArrayVES2->exchange_rate) / 2;
            $vesEquivalent         = $inputs['amount'] * $exchangeRateArrayPr;
            $rangeVES              = json_decode($exchangeRateArrayVES->range, true);

            if ($vesEquivalent < 15 || ($vesEquivalent > $rangeVES[0] && $vesEquivalent < $rangeVES[1])) {
                $priceToReturn  = $exchangeRateArrayVES->exchange_rate;
                $exchangeRateID = $exchangeRateArrayVES->id;
                $usdEquivalent  = $inputs['amount'] * $exchangeRateArrayVES->exchange_rate;
            } else {
                $priceToReturn  = $exchangeRateArrayVES2->exchange_rate;
                $exchangeRateID = $exchangeRateArrayVES2->id;
                $usdEquivalent  = $inputs['amount'] * $exchangeRateArrayVES2->exchange_rate;
            }
        } elseif ($inputs['receiver'] === 'VES') {
            $akbFee                = $inputs['ajax_fee_percent'] ?? env('WALLETS_FEE_TO_VEN');
            $exchangeRateArrayVES  = self::getExchangeRate(
                'USD',
                'United States',
                'VES',
                'Venezuela'
            )[0];
            $exchangeRateArrayVES2 = self::getExchangeRate(
                'USD',
                'United States',
                'VES2',
                'Venezuela'
            )[0];
            $vesEquivalent         = $inputs['amount'];

            $rangeVES = json_decode($exchangeRateArrayVES->range, true);

            if ($vesEquivalent < 15 || ($vesEquivalent > $rangeVES[0] && $vesEquivalent < $rangeVES[1])) {
                $priceToReturn  = $exchangeRateArrayVES->exchange_rate;
                $exchangeRateID = $exchangeRateArrayVES->id;
            } else {
                $priceToReturn  = $exchangeRateArrayVES2->exchange_rate;
                $exchangeRateID = $exchangeRateArrayVES2->id;
            }
        } elseif ($inputs['sender'] === 'USD' && $inputs['receiver'] === 'USD') {
            $envFee = $intention === 'withdraw' ? env('WALLETS_USD_USD_WITHDRAW')
                : env('WALLETS_USD_USD_CHARGE');
            $akbFee = $inputs['ajax_fee_percent'] ?? $envFee;

            $exchangeRateArray = self::getExchangeRate(
                $inputs['sender'],
                $inputs['sender_country'],
                $inputs['receiver'],
                $inputs['receiver_country']
            )[0];
            $exchangeRateID    = $exchangeRateArray->id;
            $priceToReturn     = $exchangeRateArray->exchange_rate;
        } else {
            $exchangeRateArray = self::getExchangeRate(
                $inputs['sender'],
                $inputs['sender_country'],
                $inputs['receiver'],
                $inputs['receiver_country']
            )[0];
            $exchangeRateID    = $exchangeRateArray->id;
            $priceToReturn     = $exchangeRateArray->exchange_rate;
        }

        $fee           = ($priceToReturn * $akbFee) / 100;
        $originalPrice = $priceToReturn;
        $priceToReturn -= $fee;

        return [$priceToReturn, $usdEquivalent, $exchangeRateID, $akbFee, 'original' => $originalPrice];
    }

    /**
     * Assign current assist payment operation to an online operator, if there's one.
     *
     * @param UserExchangeTransactions $transaction
     *
     * @return int|null
     */
    public static function assignExchangeOperator(UserExchangeTransactions $transaction): ?int
    {
        $operators = User::where(['is_logged_in' => true])
            ->where('is_idle', '=', 0)
            ->where('role_id', '=', 4)//Next to only some guys
            ->with([
                'assignedExchangesChats' => static function ($query) {
                    $query->where('status', '=', 0);
                }
            ])
            ->get()
            ->toArray();
        $special   = null;

        //1st Scenario. No operators Online.
        if (count($operators) === 0) {
            return null;
        }

        foreach ($operators as $key => $operator) {
            if (count($operator['assigned_exchanges_chats']) >= 5) {
                unset($operators[$key]);
            }

            if ($operator['id'] === 893 && count($operator['assigned_exchanges_chats']) < 3) {
                $special = $operator;
            }
        }

        if (count($operators) === 0) {
            return null;
        }

        usort($operators, static function ($a, $b) {
            return count($a['assigned_exchanges_chats']) <=> count($b['assigned_exchanges_chats']);
        });


        $transaction->attended_by = $special !== null ? $special['id'] : $operators[0]['id'];
        $transaction->attended_at = Carbon::now()->toDateTimeString();
        $transaction->save();

        return $special !== null ? $special['id'] : $operators[0]['id'];
    }

    /**
     * Assign current assist payment operation to an online operator, if there's one.
     *
     * @param UserExchangeTransactions $transaction
     * @param User                     $user
     *
     * @return string
     */
    public static function directAssignExchangeOperator(UserExchangeTransactions $transaction, User $user): string
    {
        unset($transaction['edtDate']);
        $previousAssignations               = $transaction->previous_assignations;
        $previousAssignations[]             = $transaction->attended_by;
        $forcedTransferDates                = $transaction->forced_transfer_dates;
        $forcedTransferDates[]              = $transaction->attended_at;
        $transaction->attended_by           = $user->id;
        $transaction->attended_at           = Carbon::now()->toDateTimeString();
        $transaction->forced_transfer       = 1;
        $transaction->previous_assignations = $previousAssignations;
        $transaction->forced_transfer_dates = $forcedTransferDates;
        $transaction->save();

        Pusher::trigger('operator-' . $user->id . '-channel', 'transaction-order',
            [
                'message' => 'Se le ha asignado una operación de cambio. Tracking ID: ' .
                    $transaction->tracking_id
            ]);

        Pusher::trigger(
            'transactions-channel',
            'queue-event',
            [
                'order_id'    => $transaction->id,
                'operator_id' => $user->id
            ]
        );

        return 'true';
    }

    /**
     * @param User $user
     */
    public static function assignExchangeTransactions(User $user): void
    {

        $userTransactions = UserExchangeTransactions::where(
            [
                'attended_by' => $user->id,
                'payment_way' => 'cash_deposit',
                'status'      => 0
            ]
        )->count();

        if ($userTransactions < 5) {
            $transactionsInQueue = UserExchangeTransactions::where(
                [
                    'attended_by' => null,
                    'payment_way' => 'cash_deposit',
                    'status'      => 0
                ]
            )
                ->orderBy('id', 'ASC')
                ->limit(5 - $userTransactions)
                ->get();

            if (count($transactionsInQueue) > 0) {
                foreach ($transactionsInQueue as $transaction) {
                    unset($transaction['edtDate']);
                    $transaction->attended_by = $user->id;
                    $transaction->attended_at = Carbon::now()->toDateTimeString();
                    $transaction->save();

                    //Emit Pusher Events
                    //1. Queue
                    Pusher::trigger('transactions-channel',
                        'queue-event',
                        [
                            'message'     => 'An operator has been connected',
                            'order_id'    => $transaction->id,
                            //Many operations out of the queue
                            'many_out'    => count($transactionsInQueue),
                            'operator_id' => $user->id
                        ]
                    );
                }
            }
        }
    }

    /**
     * @param User $user
     */
    public static function assignForcedExchangeTransactions(User $user): void
    {
        $fTransactionsInQueue = UserExchangeTransactions::where(
            [
                'attended_by'     => null,
                'payment_way'     => 'cash_deposit',
                'status'          => 0,
                'forced_transfer' => 1
            ]
        )
            ->orderBy('id', 'ASC')
            ->get();

        if (count($fTransactionsInQueue) > 0) {
            foreach ($fTransactionsInQueue as $transaction) {
                unset($transaction['edtDate']);
                $transaction->attended_by = $user->id;
                $transaction->attended_at = Carbon::now()->toDateTimeString();
                $transaction->save();

                Pusher::trigger('operator-' . $user->id . '-channel', 'transaction-order',
                    [
                        'message' => 'Se le ha asignado una operación de cambio. Tracking ID: ' .
                            $transaction->tracking_id
                    ]);

                Pusher::trigger(
                    'transactions-channel',
                    'queue-event',
                    [
                        'order_id'    => $transaction->id,
                        'operator_id' => $user->id
                    ]
                );
            }
        }
    }


    /**
     * @param int $userID
     */
    public static function watchForcedTransfer(int $userID): void
    {
        $userTransactions = UserExchangeTransactions::where(
            [
                'attended_by' => $userID,
                'payment_way' => 'cash_deposit',
                'status'      => 0
            ]
        )->get();

        if (count($userTransactions) === 0) {
            return;
        }

        $operators = User::where(['is_logged_in' => true])
            ->where(['is_idle' => 0, 'role_id' => 4])
            ->with([
                'assignedExchangesChats' => static function ($query) {
                    $query->where('status', '=', 0);
                }
            ])
            ->get()
            ->toArray();

        //If there's no operators, this transaction goes into Queue.
        if (count($operators) === 0) {
            foreach ($userTransactions as $transaction) {
                unset($transaction['edtDate']);
                $previousAssignations               = $transaction->previous_assignations;
                $previousAssignations[]             = $transaction->attended_by;
                $forcedTransferDates                = $transaction->forced_transfer_dates;
                $forcedTransferDates[]              = $transaction->attended_at;
                $transaction->attended_by           = null;
                $transaction->attended_at           = null;
                $transaction->forced_transfer       = 1;
                $transaction->previous_assignations = $previousAssignations;
                $transaction->forced_transfer_dates = $forcedTransferDates;
                $transaction->save();
            }

            Pusher::trigger('transactions-channel',
                'no-operators',
                [
                    'message' => 'No operators left'
                ]
            );

            return;
        }

        usort($operators, static function ($a, $b) {
            return count($a['assigned_exchanges_chats']) <=> count($b['assigned_exchanges_chats']);
        });

        $operatorKey = 0;
        foreach ($userTransactions as $transaction) {
            unset($transaction['edtDate']);
            //Reset operators list
            if (!isset($operators[$operatorKey])) {
                $operatorKey = 0;
            }
            $previousAssignations               = $transaction->previous_assignations;
            $previousAssignations[]             = $transaction->attended_by;
            $forcedTransferDates                = $transaction->forced_transfer_dates;
            $forcedTransferDates[]              = $transaction->attended_at;
            $transaction->attended_by           = $operators[$operatorKey]['id'];
            $transaction->attended_at           = Carbon::now()->toDateTimeString();
            $transaction->forced_transfer       = 1;
            $transaction->previous_assignations = $previousAssignations;
            $transaction->forced_transfer_dates = $forcedTransferDates;
            $transaction->save();

            Pusher::trigger('operator-' . $operators[$operatorKey]['id'] . '-channel', 'transaction-order',
                [
                    'message' => 'Se le ha asignado una operación de cambio. Tracking ID: ' .
                        $transaction->tracking_id
                ]);

            Pusher::trigger(
                'transactions-channel',
                'queue-event',
                [
                    'order_id'    => $transaction->id,
                    'operator_id' => $operators[$operatorKey]['id']
                ]
            );
            $operatorKey++;
        }
    }


    /**
     * @param array        $inputs
     *
     * @param boolean|null $noMinimum
     *
     * @return bool|RedirectResponse
     */
    public static function checkEnableToOperate(array $inputs, bool $noMinimum = null)
    {
        $usdEquivalent = $inputs['to_send'];

        if ($inputs['sender'] !== 'USD') {
            $exchangeRates = HelpersController::getExchangeRates();
            $usdEquivalent = (float)str_replace(',', '',
                    $inputs['to_send']) / $exchangeRates['rates'][$inputs['sender']];
        }

        $userProfile = Auth::user()->personProfileObject();

        if ($noMinimum === null && $usdEquivalent > 100 && $userProfile->profileCompletition !== 1) {
            return Redirect::to('/');
        }

        if (self::checkHours()) {
            return Redirect::to('/');
        }

        return true;
    }

    /**
     * Check if the site is open.
     *
     * @return int
     */
    public static function checkHours(): int
    {
        $closeHour       = Carbon::createFromTime(17, 0, 0, 'UTC')->getTimestamp();
        $currentHour     = Carbon::now('UTC')->getTimestamp();
        $currentDay      = Carbon::now('UTC');
        $currentDay      = $currentDay->dayOfWeek;
        $websiteSettings = WebsiteSettings::find(1);

        if ($currentDay !== 0 && $currentDay !== 6 && $websiteSettings['settings']['is_active'] !== 0) {
            return 0;
        }

        if ($currentDay === 6 && $currentHour < $closeHour && $websiteSettings['settings']['is_active'] !== 0) {
            return 0;
        }

        return 1;
    }

    /**
     * @param $to_send
     * @param $sender
     *
     * @return bool
     */
    public static function amountValidation($to_send, $sender): bool
    {
        if ($sender === 'USD' && $to_send >= 15.00) {

            return false;

        } elseif ($sender !== 'VES') {

            $exchange_data = MarketData::getExchangeRates();

            if ($to_send / $exchange_data[$sender] >= 15.00) {
                return false;
            }

        } elseif ($sender === 'VES') {
            $exchangePriceData = Banker::getExchangeRate(
                'VES',
                'Venezuela',
                'USD',
                'United States'
            )[0];
            $price             = $exchangePriceData->exchange_rate;
            $akbFee            = env('FEE_FROM_VEN'); //TODO Dynamic
            $fee               = ($price * $akbFee) / 100;
            $price             -= $fee;

            if ($to_send / $price >= 15) {
                return false;
            }

        }

        return true;
    }

    /**
     * Assign current assist payment operation to an online operator, if there's one.
     *
     * @param UserWalletsTransactions $transaction
     *
     * @return int|null
     */
    public static function assignWalletsOperator(UserWalletsTransactions $transaction): ?int
    {
        $operators = User::where(['is_logged_in' => true])
            ->where('is_idle', '=', 0)
            ->where('role_id', '=', 9)//Only Wallets Traders
            ->with([
                'assignedWalletsChats' => static function ($query) {
                    $query->where('status', '=', 0);
                }
            ])
            ->get()
            ->toArray();
//        $special   = null;

        //1st Scenario. No operators Online.
        if (count($operators) === 0) {
            return null;
        }

        foreach ($operators as $key => $operator) {
            if (count($operator['assigned_wallets_chats']) >= 3) {
                unset($operators[$key]);
            }

//            if ($operator['id'] === 893 && count($operator['assigned_wallets_chats']) < 3) {
//                $special = $operator;
//            }
        }

        if (count($operators) === 0) {
            return null;
        }

        usort($operators, static function ($a, $b) {
            return count($a['assigned_wallets_chats']) <=> count($b['assigned_wallets_chats']);
        });


        $transaction->attended_by = $operators[0]['id']; // $special !== null ? $special['id'] : $operators[0]['id'];
        $transaction->attended_at = Carbon::now()->toDateTimeString();
        $transaction->save();

        return $operators[0]['id']; // $special !== null ? $special['id'] : $operators[0]['id'];
    }

    /**
     * Assign current assist payment operation to an online operator, if there's one.
     *
     * @param UserWalletsTransactions $transaction
     * @param User                    $user
     *
     * @return string
     */
    public static function directAssignWalletsOperator(UserWalletsTransactions $transaction, User $user): string
    {
        $previousAssignations               = $transaction->previous_assignations;
        $previousAssignations[]             = $transaction->attended_by;
        $forcedTransferDates                = $transaction->forced_transfer_dates;
        $forcedTransferDates[]              = $transaction->attended_at;
        $transaction->attended_by           = $user->id;
        $transaction->attended_at           = Carbon::now()->toDateTimeString();
        $transaction->forced_transfer       = 1;
        $transaction->previous_assignations = $previousAssignations;
        $transaction->forced_transfer_dates = $forcedTransferDates;
        $transaction->save();

        Pusher::trigger('wallets-operator-' . $user->id . '-channel', 'transaction-order',
            [
                'message' => 'Se le ha asignado una operación de cambio. Tracking ID: ' .
                    $transaction->tracking_id
            ]);

        Pusher::trigger(
            'wallets-channel',
            'queue-event',
            [
                'order_id'    => $transaction->id,
                'operator_id' => $user->id
            ]
        );

        return 'true';
    }

    /**
     * @param User $user
     */
    public static function assignWalletsTransactions(User $user): void
    {

        $userTransactions = UserWalletsTransactions::where(
            [
                'attended_by' => $user->id,
                'payment_way' => 'cash',
                'status'      => 1
            ]
        )->count();

        if ($userTransactions < 3) {
            $transactionsInQueue = UserWalletsTransactions::where(
                [
                    'attended_by' => null,
                    'payment_way' => 'cash',
                    'status'      => 1
                ]
            )
                ->orderBy('id', 'ASC')
                ->limit(3 - $userTransactions)
                ->get();

            if (count($transactionsInQueue) > 0) {
                foreach ($transactionsInQueue as $transaction) {
                    $transaction->attended_by = $user->id;
                    $transaction->attended_at = Carbon::now()->toDateTimeString();
                    $transaction->save();

                    //Emit Pusher Events
                    //1. Queue
                    Pusher::trigger('wallets-channel',
                        'queue-event',
                        [
                            'message'     => 'An operator has been connected',
                            'order_id'    => $transaction->id,
                            //Many operations out of the queue
                            'many_out'    => count($transactionsInQueue),
                            'operator_id' => $user->id
                        ]
                    );
                }
            }
        }
    }

    /**
     * @param User $user
     */
    public static function assignForcedWalletsTransactions(User $user): void
    {
        $fTransactionsInQueue = UserWalletsTransactions::where(
            [
                'attended_by'      => null,
                'payment_way'      => 'cash',
                'status'           => 1,
                'type'             => 3,
                'purpose'          => 1,
                'exchange_related' => 0
                //                'forced_transfer' => 1
            ]
        )
            ->orderBy('id', 'ASC')
            ->get();

        if (count($fTransactionsInQueue) > 0) {
            foreach ($fTransactionsInQueue as $transaction) {
                $transaction->attended_by = $user->id;
                $transaction->attended_at = Carbon::now()->toDateTimeString();
                $transaction->save();

                Pusher::trigger('wallets-operator-' . $user->id . '-channel', 'transaction-order',
                    [
                        'message' => 'Se le ha asignado una operación de cambio. Tracking ID: ' .
                            $transaction->tracking_id
                    ]);

                Pusher::trigger(
                    'wallets-channel',
                    'queue-event',
                    [
                        'order_id'    => $transaction->id,
                        'operator_id' => $user->id
                    ]
                );
            }
        }
    }


    /**
     * @param int $userID
     */
    public static function watchForcedWalletsTransfer(int $userID): void
    {
        $userTransactions = UserWalletsTransactions::where(
            [
                'attended_by' => $userID,
                'payment_way' => 'cash',
                'status'      => 1
            ]
        )->get();

        if (count($userTransactions) === 0) {
            return;
        }

        $operators = User::where(['is_logged_in' => true])
            ->where('is_idle', '=', 0)
            ->where('role_id', '=', 9)//Only Wallets Traders
            ->with([
                'assignedWalletsChats' => static function ($query) {
                    $query->where('status', '=', 0);
                }
            ])
            ->get()
            ->toArray();

        //If there's no operators, this transaction goes into Queue.
        if (count($operators) === 0) {
            foreach ($userTransactions as $transaction) {
                $previousAssignations               = $transaction->previous_assignations;
                $previousAssignations[]             = $transaction->attended_by;
                $forcedTransferDates                = $transaction->forced_transfer_dates;
                $forcedTransferDates[]              = $transaction->attended_at;
                $transaction->attended_by           = null;
                $transaction->attended_at           = null;
                $transaction->forced_transfer       = 1;
                $transaction->previous_assignations = $previousAssignations;
                $transaction->forced_transfer_dates = $forcedTransferDates;
                $transaction->save();
            }

            Pusher::trigger('wallets-channel',
                'no-operators',
                [
                    'message' => 'No operators left'
                ]
            );

            return;
        }

        usort($operators, static function ($a, $b) {
            return count($a['assigned_wallets_chats']) <=> count($b['assigned_wallets_chats']);
        });

        $operatorKey = 0;
        foreach ($userTransactions as $transaction) {
            //Reset operators list
            if (!isset($operators[$operatorKey])) {
                $operatorKey = 0;
            }
            $previousAssignations               = $transaction->previous_assignations;
            $previousAssignations[]             = $transaction->attended_by;
            $forcedTransferDates                = $transaction->forced_transfer_dates;
            $forcedTransferDates[]              = $transaction->attended_at;
            $transaction->attended_by           = $operators[$operatorKey]['id'];
            $transaction->attended_at           = Carbon::now()->toDateTimeString();
            $transaction->forced_transfer       = 1;
            $transaction->previous_assignations = $previousAssignations;
            $transaction->forced_transfer_dates = $forcedTransferDates;
            $transaction->save();

            Pusher::trigger('wallets-operator-' . $operators[$operatorKey]['id'] . '-channel', 'transaction-order',
                [
                    'message' => 'Se le ha asignado una operación de cambio. Tracking ID: ' .
                        $transaction->tracking_id
                ]);

            Pusher::trigger(
                'wallets-channel',
                'queue-event',
                [
                    'order_id'    => $transaction->id,
                    'operator_id' => $operators[$operatorKey]['id']
                ]
            );
            $operatorKey++;
        }
    }

    private static $walletsList = null;

    /**
     * Get total balance in all users Wallets
     *
     * @param CurrencyWallet|null $walletModel
     * @param string|null         $date
     *
     * @return array
     */
    public static function getTotalBalanceUserWallets(CurrencyWallet $walletModel = null, string $date = null): array
    {
        setlocale(LC_MONETARY, 'en_US.UTF-8');
        if ($walletModel) {
            return self::getWalletNumbers($walletModel['relatedTransactions']);
        }

        self::$walletsList = self::$walletsList ?? CurrencyWallet::where(['status' => 1])->get()->toArray();
        $holdsDown         = 0;
        $holdsUp           = 0;
        $available         = 0;

        foreach (self::$walletsList as $wallet) {
            $walletData = self::getWalletNumbers($wallet['relatedTransactions'], true, $date);
            $holdsDown  += $walletData['holdsDown'];
            $holdsUp    += $walletData['holdsUp'];
            $available  += $walletData['available'];
        }

        return [
            'holdsUp'   => money_format('%.2n', $holdsUp),
            'holdsDown' => money_format('%.2n', $holdsDown),
            'available' => money_format('%.2n', $available)
        ];
    }

    /**
     * Get balance in movements in all users Wallets
     *
     * @param string|null $date
     *
     * @return array
     */
    public static function getWalletsBalanceMovements(string $date = null): array
    {
        setlocale(LC_MONETARY, 'en_US.UTF-8');

        self::$walletsList = self::$walletsList ?? CurrencyWallet::where(['status' => 1])->get()->toArray();

        return self::walletsTransactionsOrganizedByDate(self::$walletsList, $date);
    }

    /**
     * @param array       $relatedTransactions
     * @param bool        $onlyWallets
     * @param string|null $date
     * @param bool|null   $onlyAvailable
     *
     * @return array
     */
    private static function getWalletNumbers(
        array $relatedTransactions,
        bool $onlyWallets = false,
        string $date = null,
        bool $onlyAvailable = null
    ): array {
        $reloads     = [];
        $withdrawals = [];
        $holdsDown   = [];
        $holdsUp     = [];

        foreach ($relatedTransactions as $rTransaction) {
            if ($date && $rTransaction['created_at'] < $date) {
                continue;
            }

            if ($onlyWallets && $rTransaction['exchange_related'] === 1) {
                continue;
            }

            if ($rTransaction['type'] !== 3 && $rTransaction['status'] === 2) {
                continue;
            }

            if ($rTransaction['type'] === 1 && $rTransaction['status'] !== 3) {
                $reloads[] = $rTransaction['amount'];
            }

            if ($rTransaction['type'] === 2) {
                $withdrawals[] = $rTransaction['amount'];
            }

            if ($rTransaction['type'] === 3 && ($rTransaction['status'] === 1 || $rTransaction['status'] === 6)) { //Is Hold Type
                if ($rTransaction['purpose'] === 1) {
                    if ($onlyWallets && $rTransaction['is_payed'] === 1) {
                        $holdsUp[] = $rTransaction['amount'];
                    }
                    if (!$onlyWallets) {
                        $holdsUp[] = $rTransaction['amount'];
                    }
                } else {
                    $holdsDown[] = $rTransaction['amount'];
                }
            }

            if (!$onlyWallets && $rTransaction['type'] === 4 && $rTransaction['status'] === 1) {
                if ($rTransaction['purpose'] === 1) {
                    $reloads[] = $rTransaction['amount'];
                } else {
                    $withdrawals[] = $rTransaction['amount'];
                }
            }
        }

        $reloads     = array_sum($reloads);
        $withdrawals = array_sum($withdrawals);
        $holdsUp     = array_sum($holdsUp);
        $holdsDown   = array_sum($holdsDown);

        if ($onlyAvailable === null) {
            $available = self::getWalletNumbers($relatedTransactions, false, null, true)['available'];
        } else {
            $available = ($reloads - $withdrawals) - $holdsDown;
        }

//        dd($reloads, $withdrawals, $holdsUp, $holdsDown, $available);

        return [
            'reloads'     => $reloads,
            'withdrawals' => $withdrawals,
            'holdsUp'     => $holdsUp,
            'holdsDown'   => $holdsDown,
            'available'   => $available
        ];
    }

    /**
     * Organize all wallets transactions list by date.
     *
     * @param array       $walletsList
     * @param string|null $date
     *
     * @return array
     */
    private static function walletsTransactionsOrganizedByDate(array $walletsList, string $date = null): array
    {
        $groupByDates = [];

        foreach ($walletsList as $wallet) {
            $allTransactions = $wallet['relatedTransactions'];

            foreach ($allTransactions as $key => $transaction) {
                if ($date && $transaction['created_at'] < $date) {
                    unset($allTransactions[$key]);
                }

                if (($transaction['type'] === 3 && $transaction['status'] === 2) ||
                    ($transaction['type'] !== 3 && $transaction['status'] === 2) ||
                    ($transaction['type'] === 3 && $transaction['status'] === 1 &&
                        ($transaction['purpose'] === 1 && $transaction['is_payed'] === null))) {
                    unset($allTransactions[$key]);
                }
            }

            foreach ($allTransactions as $key => $transaction) {
                $explodedDate                                                 = explode(
                    '-',
                    $transaction['created_at']
                );
                $month                                                        = date(
                    'F',
                    mktime(0, 0, 0, $explodedDate[1], 10)
                );
                $day                                                          = substr($explodedDate[2], 0, 2);
                $groupByDates[$explodedDate[0]][$month][$day]['operations'][] = $transaction;

                usort($groupByDates[$explodedDate[0]][$month][$day]['operations'], static function ($a, $b) {
                    return $b['id'] <=> $a['id'];
                });
            }
        }

        foreach ($groupByDates as $keyY => $year) {
            foreach ($year as $keyM => $month) {
                foreach ($month as $keyD => $day) {
                    $groupByDates[$keyY][$keyM][$keyD]['numbers'] = self::getWalletDayNumbers($day['operations']);
                }
            }
        }

        return $groupByDates;
    }

    /**
     * @param array $relatedTransactions
     *
     * @return array
     */
    private static function getWalletDayNumbers(array $relatedTransactions): array
    {
        setlocale(LC_MONETARY, 'en_US.UTF-8');
        $reloads      = [];
        $withdrawals  = [];
        $holdsDown    = [];
        $holdsUp      = [];
        $holdsUpTotal = [];

        foreach ($relatedTransactions as $rTransaction) {
            if ($rTransaction['exchange_related'] === 1 ||
                ($rTransaction['type'] !== 3 && $rTransaction['status'] === 2)) {
                continue;
            }

            if ($rTransaction['type'] === 1 && $rTransaction['status'] !== 3) {
                $reloads[] = $rTransaction['amount'];
            }

            if ($rTransaction['type'] === 2) {
                $withdrawals[] = $rTransaction['amount'];
            }

            if ($rTransaction['type'] === 3 && ($rTransaction['status'] === 1 || $rTransaction['status'] === 6)) { //Is Hold Type
                if ($rTransaction['purpose'] === 1) {
                    if (isset($holdsUp[$rTransaction['payment_way']])) {
                        $holdsUp[$rTransaction['payment_way']] += $rTransaction['amount'];
                    } else {
                        $holdsUp[$rTransaction['payment_way']] = $rTransaction['amount'];
                    }
                    $holdsUpTotal[] = $rTransaction['amount'];
                } else {
                    $holdsDown[] = $rTransaction['amount'];
                }
            }
        }

        $reloads      = array_sum($reloads);
        $withdrawals  = array_sum($withdrawals);
        $holdsUpTotal = array_sum($holdsUpTotal);
        $holdsDown    = array_sum($holdsDown);

        return [
            'reloads'      => money_format('%.2n', $reloads),
            'withdrawals'  => money_format('%.2n', $withdrawals),
            'holdsUp'      => $holdsUp,
            'holdsUpTotal' => money_format('%.2n', $holdsUpTotal),
            'holdsDown'    => money_format('%.2n', $holdsDown)
        ];
    }
}
