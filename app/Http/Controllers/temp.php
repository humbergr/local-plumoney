<?php 
if ($inputs['sender'] !== 'USD') {
    $exchangeRates = HelpersController::getExchangeRates();
    $usdToCharge   = $inputs['to_send'] / $exchangeRates['rates'][$inputs['sender']];
    $usdBridge     = $inputs['to_receive'];
    if ($inputs['receiver'] !== 'USD') {
        $usdBridge = (float) str_replace(
            ',',
            '',
            $inputs['to_receive']
        ) / $exchangeRates['rates'][$inputs['receiver']];
    }

    if ($inputs['receiver'] === 'VES') {
        $usdBridge = $usdToCharge;
    }

    $fees = HelpersController::calculateFees(
        $usdToCharge,
        'card',
        $inputs['pay_method_country'],
        $exchangeRates['rates'][$inputs['sender']],
        $usdBridge,
        (int) env('QBPAY') === 1 ? true : null
    );
    //$fees[1]       = round($fees[1] / $exchangeRates['rates'][$inputs['sender']], 2);
} else {
    $fees = HelpersController::calculateFees(
        $inputs['to_send'],
        'card',
        $inputs['pay_method_country']
    );
    $usdToCharge = $inputs['to_send'];
}

if ((int) env('QBPAY') !== 1) {
    try {


        $charge = Charge::create([
            'amount'      => $fees[1] * 100,
            'currency'    => $inputs['sender'],
            'customer'    => Auth::user()->stripe_id,
            'source'      => $inputs['pay_method'],
            'description' => $description,
            'metadata'    => ['exchange_transaction_id' => $userNewTransaction->id],
        ]);

        $userNewTransaction->status      = 0;
        $userNewTransaction->is_revised  = 0;
        $userNewTransaction->is_payed    = 1;
        $userNewTransaction->payed_at    = Carbon::now()->toDateTimeString();
        $userNewTransaction->payed_by    = 'Stripe: ' . $charge->id;
        $userNewTransaction->stripe_data = $charge;

        //Create reload Wallet Transaction
        $walletTransactionID = $banker->rechargeWalletCredits(
            $walletID,
            $usdToCharge,
            $isoCurrency,
            'Stripe',
            null,
            $inputs
        );

        $userNewTransaction->wallet_transaction_id = $walletTransactionID;
        $userNewTransaction->save();

        //Create hold Wallet Transaction
        $banker->holdWalletCredits(
            $walletID,
            $usdToCharge,
            $isoCurrency,
            null,
            $walletTransactionID,
            2,
            'Stripe',
            null,
            $inputs
        );
    } catch (Card $e) {
        $userNewTransaction->status     = 3;
        $userNewTransaction->is_revised = 1;
        $userNewTransaction->failed_at  = Carbon::now()->toDateTimeString();
        $userNewTransaction->failed_by  = $e->getMessage();
        $userNewTransaction->save();

        return Redirect::to('/send-money')->with('error', $e->getMessage());
    }
} else {
    $settings = WebsiteSettings::find(1);
    $client   = new Client([
        'base_uri' => env('APP_DEBUG') === true ? 'https://sandbox.api.intuit.com' : 'https://api.intuit.com',
    ]);
    try {
        $response = $client->request(
            'POST',
            '/quickbooks/v4/payments/charges',
            [
                'headers' => [
                    'content-type'  => 'application/json',
                    'accept'        => 'application/json',
                    'authorization' => 'Bearer ' . $settings->qb_access_token_key,
                    'request-id'    => uniqid('ath_qb_', false),
                ],
                //TODO calculate USD to amount
                'json'    => [
                    'currency' => 'USD',
                    'amount'   => $fees[1],
                    'context'  => [
                        'mobile'      => false,
                        'isEcommerce' => true,
                    ],
                    'card'     => [
                        'number'   => str_replace(' ', '', $inputs['qb_card']['cardNumber']),
                        'expMonth' => $inputs['qb_card']['card_month'],
                        'expYear'  => $inputs['qb_card']['card_year'],
                        'cvc'      => $inputs['qb_card']['card_cvv'],
                    ],
                ],
            ]
        );

        $qbResponse = \GuzzleHttp\json_decode($response->getBody()->getContents(), true);
        $badStatus  = [
            'DECLINED',
            'CANCELLED',
            'REFUNDED',
        ];

        if (!in_array($qbResponse['status'], $badStatus, true)) {
            $userNewTransaction->status      = 0;
            $userNewTransaction->is_revised  = 0;
            $userNewTransaction->is_payed    = 1;
            $userNewTransaction->payed_at    = Carbon::now()->toDateTimeString();
            $userNewTransaction->payed_by    = 'Quickbook: ' . $qbResponse['id'];
            $userNewTransaction->stripe_data = $qbResponse;

            //Create reload Wallet Transaction
            $walletTransactionID = $banker->rechargeWalletCredits(
                $walletID,
                $usdToCharge,
                $isoCurrency,
                'QuickBook',
                null,
                $inputs
            );

            $userNewTransaction->wallet_transaction_id = $walletTransactionID;
            $userNewTransaction->save();

            //Create hold Wallet Transaction
            $banker->holdWalletCredits(
                $walletID,
                $usdToCharge,
                $isoCurrency,
                null,
                $walletTransactionID,
                2,
                'QuickBook',
                null,
                $inputs
            );
        } else {
            $userNewTransaction->status     = 3;
            $userNewTransaction->is_revised = 1;
            $userNewTransaction->failed_at  = Carbon::now()->toDateTimeString();
            $userNewTransaction->failed_by  = 'Quickbook: ' . $qbResponse['id'];
            $userNewTransaction->save();

            return Redirect::to('/send-money')->with('error', 'Operación Fallida');
        }
    } catch (RequestException $e) {
        $errors = \GuzzleHttp\json_decode(
            $e->getResponse()->getBody(),
            true
        );
        $userNewTransaction->status     = 3;
        $userNewTransaction->is_revised = 1;
        $userNewTransaction->failed_at  = Carbon::now()->toDateTimeString();
        $userNewTransaction->failed_by  = $errors['errors'][0]['message'];
        $userNewTransaction->save();

        return Redirect::to('/send-money')->with('error', $errors['errors'][0]['message']);
    }
}

Pusher::trigger(
    'my-channel',
    'transaction-order',
    ['message' => Auth::user()->name . ' has created a new transaction order.']
);

if (isset($inputs['bonus_amount'])) {

    $account = DestinationAccount::find($inputs['destination_id']);

    BonusCoupon::create([
        'merchant_id'          => Auth::user()->id,
        'transaction_id'       => $userNewTransaction->id,
        'receiver_id_document' => $account->id_number,
        'receiver_account'     => $account->account_number,
    ]);
}

$account = DestinationAccount::find($inputs['destination_id']);

$this->sendDestinationMail(
    $account->email,
    $account->name . ' ' . $account->lastname,
    $userNewTransaction->receiver_fiat_amount,
    $inputs['receiver']
);

return Redirect::to('/transaction-success/' . $userNewTransaction->id);

?>