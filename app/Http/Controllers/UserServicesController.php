<?php

namespace App\Http\Controllers;

use App;
use App\Akb\Banker;
use App\BankAccount;
use App\CurrencyWallet;
use App\DestinationAccount;
use App\GenericPaymentsMethods;
use App\Mail\MerchantTransactionStatus;
use App\Mail\MerchantTransactionStatus2;
use App\Mail\ReceiveWalletsMoneyMail;
use App\Mail\SendWalletsMoneyMail;
use App\Mail\TransactionStatusNoteIncoming;
use App\StatusNotesIncoming;
use App\SubjectsStatusIncoming;
use App\User;
use App\UserExchangeTransactions;
use App\UserPersonProfile;
use App\UserWalletsTransactions;
use App\WalletTransactionMessage;
use App\WebsiteSettings;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Pusher\Laravel\Facades\Pusher;
use Stripe\Charge;
use Stripe\Error\Card;
use Stripe\Stripe;
use URL;

class UserServicesController extends Controller
{
    public function getWallets()
    {

        if (Auth::user()) {
            if (Auth::user()->personProfile->approval_status !== 2) {
                return Redirect::to('/user-info');
            }

            $activeTransaction = UserWalletsTransactions::where(
                [
                    'user_id'     => Auth::user()->id,
                    'status'      => 1,
                    'type'        => 3,
                    'payment_way' => 'cash',
                ]
            )->orderBy('created_at', 'DESC')->first();

            if ($activeTransaction) {
                return Redirect::to('/wallets/transaction/' . $activeTransaction->id)->with(
                    'error',
                    'Posee una transacción activa.'
                );
            }

            $userWallets = CurrencyWallet::where([
                'user_id'  => Auth::user()->id,
                'status'   => 1,
                'currency' => 'USD',
            ])
                ->get()
                ->toArray();

            if (empty($userWallets)) {
                $banker = new Banker;
                $userWallets = [$banker->createWallet('USD', null, true)->toArray()];
            }

            return view('services.wallets-info')->with(compact('userWallets'));
        }
        Session::flash('error', 'No ha inciado sesión');

        return redirect('/');
    }

    public function filterWalletTransactions()
    {
        $inputs    = \request()->all();
        $startDate = Carbon::createFromFormat('Y-m-d H:i:s', $inputs['start']);
        $endDate   = Carbon::createFromFormat('Y-m-d H:i:s', $inputs['end']);
        if ($inputs['zoneO'] < 0) {
            $startDate->addMinutes($inputs['zoneO'] * -1);
            $endDate->addMinutes($inputs['zoneO'] * -1);
        } else {
            $startDate->subMinutes($inputs['zoneO']);
            $endDate->addMinutes($inputs['zoneO']);
        }

        return UserWalletsTransactions::where(['wallet_id' => $inputs['wallet_id']])
            ->where('created_at', '>', $startDate)
            ->where('created_at', '<', $endDate)
            ->with('relatedTransaction')
            ->with([
                'relatedWalletTransaction' => static function ($query) {
                    $query->with('relatedTransaction');
                },
            ])
            ->orderBy('created_at', 'desc')
            ->orderBy('id', 'desc')
            ->get()
            ->toArray();
    }

    public function getCharge()
    {

        if (Auth::user()->personProfile->approval_status !== 2) {
            return Redirect::to('/user-info');
        }

        $activeTransaction = UserWalletsTransactions::where(
            [
                'user_id'     => Auth::user()->id,
                'status'      => 1,
                'type'        => 3,
                'payment_way' => 'cash',
            ]
        )->orderBy('created_at', 'DESC')->first();

        if ($activeTransaction) {
            return Redirect::to('/wallets/transaction/' . $activeTransaction->id)->with(
                'error',
                'Posee una transacción activa.'
            );
        }

        $inputs      = \request()->all();

        if (!isset($inputs['identity'])) {
            Session::flash('error', 'No se ha indicado un wallet');

            return redirect('/wallets');
        }

        $userWallets = CurrencyWallet::where([
            'user_id'  => Auth::user()->id,
            'status'   => 1,
            'currency' => 'USD',
        ])
            ->get()
            ->toArray();

        if (empty($userWallets)) {
            $banker = new Banker;
            $userWallets = [$banker->createWallet('USD', null, true)->toArray()];
        }

        $userWallet = null;
        foreach ($userWallets as $wallet) {
            if ($wallet['hash'] === $inputs['identity']) {
                $userWallet = $wallet;
            }
        }

        if ($userWallet) {
            $qbPay = env('QBPAY') === '1';

            return view('services.charge')->with(compact(['userWallet', 'userWallets', 'qbPay']));
        }

        Session::flash('error', 'La wallet está inhabilitada');

        return redirect('/wallets');
    }

    /**
     * @param $id
     *
     * @return mixed
     */
    public function getBankAccounts($id)
    {
        return BankAccount::where([
            'wallet_transaction_id' => $id,
            'canceled'              => null,
        ])
            ->orderBy('id', 'ASC')
            ->get();
    }

    /**
     * @return bool|RedirectResponse
     */
    private function validateActiveChat()
    {

        $user              = Auth::user();
        $activeTransaction = UserWalletsTransactions::where(
            [
                'user_id' => $user->id,
                'status'  => 0,
            ]
        )->orderBy('created_at', 'DESC')->first();

        if ($activeTransaction && $activeTransaction->payment_way === 'cash_deposit') {
            return Redirect::back()
                ->with('error', 'Posee una transacción activa.');
        }

        return null;
    }

    /**
     * @return RedirectResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function postWalletCharge(): RedirectResponse

    {


        if (request()->paymentMethod  === 'card') {
            return Redirect::back()->with('error', 'El método de recarga de saldo con tarjeta de crédito está en mantenimiento.');

        }



        //If has a chat active, redirect to it, else will retourn null and code continues.
        $this->validateActiveChat();

        $inputs                     = request()->all();
        $banker                     = new Banker;
        $inputs['receiver']         = 'USD';
        $inputs['receiver_country'] = 'United States';

        if ($banker::amountValidation((float) str_replace(',', '', $inputs['to_send']), $inputs['sender'])) {
            return Redirect::back()->with('error', 'El monto a enviar debe ser igual o mayor a 15 USD o equivalente.');
        }

        if ((float) str_replace(',', '', $inputs['to_send']) < 1) {
            return Redirect::back()->with('error', 'Ingrese monto a enviar.');
        }

        //cross site scripting validation
        $inputs['to_send'] = (float) str_replace(',', '', $inputs['to_send']);
        $inputs['amount']  = $inputs['to_send'];
        $exchangePriceData = Banker::walletsGetPrice($inputs);
        $exchangePrice     = $exchangePriceData[0];
        $controlToReceive  = round($inputs['to_send'] * $exchangePriceData[0], 2);
        $to_receive_float  = (float) str_replace(',', '', $inputs['to_receive']);

        if ((string) $to_receive_float !== (string) $controlToReceive) {
            Session::flash('error', 'El monto a recibir es incorrecto. Por favor, revise e intente de nuevamente');

            return redirect('/wallets/charge?identity=' . $inputs['identity']);
        }

        $banker::checkEnableToOperate($inputs, true);

        /**
         * Si es card, carga de una, caso contrario crea un hold.
         */
        $userWallet = CurrencyWallet::where([
            'user_id' => Auth::user()->id,
            'hash'    => $inputs['identity'],
        ])
            ->first()
            ->toArray();

        //TODO Validate if wallet exist
        if ($userWallet === null) {
            return Redirect::back()->with('error', 'Wallet no encontrada');
        }

        if ($inputs['paymentMethod'] === 'card') {
            if ((int) env('QBPAY') !== 1) {
                Stripe::setApiKey(env('STRIPE_PRIVATE_KEY'));
            }

            $description = 'American Time Holding.';

            if ($inputs['sender'] !== 'USD') {
                $exchangeRates = HelpersController::getExchangeRates();
                $usdToCharge   = $inputs['to_send'] / $exchangeRates['rates'][$inputs['sender']];
                $usdBridge     = $inputs['to_receive'];

                $fees = HelpersController::calculateFees(
                    $usdToCharge,
                    'card',
                    $inputs['pay_method_country'],
                    $exchangeRates['rates'][$inputs['sender']],
                    $usdBridge,
                    (int) env('QBPAY') === 1 ? true : null
                );
            } else {
                $fees = HelpersController::calculateFees(
                    $inputs['to_send'],
                    'card',
                    $inputs['pay_method_country']
                );
            }

            //Create hold Wallet Transaction
            $holdID = $banker->holdWalletCredits(
                $userWallet['id'],
                $controlToReceive,
                $userWallet['currency'],
                null,
                null,
                1,
                (int) env('QBPAY') === 1 ? 'QuickBook' : 'Stripe',
                null,
                $inputs
            );

            if ((int) env('QBPAY') !== 1) {
                try {
                    $charge = Charge::create([
                        'amount'      => $fees[1] * 100,
                        'currency'    => $inputs['sender'],
                        'customer'    => Auth::user()->stripe_id,
                        'source'      => $inputs['pay_method'],
                        'description' => $description,
                        'metadata'    => ['wallet_recharge_transaction_id' => $inputs['tracking_id']],
                    ]);

                    //Paid
                    $banker->payWalletTransaction($holdID, null, $charge);
                } catch (Card $e) {
                    //Paid
                    $banker->rejectWalletTransaction($holdID, $e->getMessage());

                    Session::flash('error', $e->getMessage());

                    return Redirect::to('/wallets?identity' . $inputs['identity']);
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
                                    'number'   => $inputs['qb_card']['cardNumber'],
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
                        //Paid
                        $banker->payWalletTransaction($holdID, null, null, $qbResponse);
                    } else {
                        $banker->rejectWalletTransaction($holdID, 'QuickBook: Declined');
                        Session::flash('error', 'Operación Fallida');

                        return Redirect::to('/wallets?identity' . $inputs['identity']);
                    }
                } catch (RequestException $e) {
                    $errors = \GuzzleHttp\json_decode(
                        $e->getResponse()->getBody(),
                        true
                    );
                    $banker->rejectWalletTransaction($holdID, $errors['errors'][0]['message']);

                    Session::flash('error', $errors['errors'][0]['message']);

                    return Redirect::to('/wallets?identity' . $inputs['identity']);
                }
            }

            //END
            Session::flash('success', 'Su recarga se ha completado con éxito.');

            return Redirect::to('/wallets/details/' . $holdID);
        }

        if ($inputs['paymentMethod'] === 'ath_prepaid') {

            /**
             * 1. Verify the card.
             */
            $client = new Client(['base_uri' => 'https://americantimeholding.com/api/']);
            //$client   = new Client(['base_uri' => 'http://american.test/api/']);
            $response = $client->request(
                'GET',
                'card/' . $inputs['ath_prepaid_code'] . '?api_token=' . env('ATH_TOKEN')
            );
            $cardInfo = \GuzzleHttp\json_decode($response->getBody()->getContents());
            // dd($cardInfo);

            if (@$cardInfo->card === "invalid card") {
                Session::flash(
                    'error',
                    'invalid card.' .
                    ' Trata de nuevo.'
                );

                return redirect()->back();
            }

            if ($cardInfo->active !== 1 || $cardInfo->uso !== null
                || $cardInfo->solicitud !== 'APROVED' || (float) $cardInfo->monto !== $inputs['to_send']) {
                Session::flash(
                    'error',
                    'El monto no coincide con el valor de la tarjeta de regalo o la tarjeta de regalo es inválida.' .
                    ' Trata de nuevo.'
                );

                return redirect('/wallets/charge?identity=' . $inputs['identity']);
            }

            //Create hold Wallet Transaction
            $holdID = $banker->holdWalletCredits(
                $userWallet['id'],
                $controlToReceive,
                $userWallet['currency'],
                $inputs['pay_method'],
                null,
                1,
                'American Time Holding',
                null,
                $inputs
            );

            $response = $client->request(
                'POST',
                'card-payment/' . $inputs['ath_prepaid_code'],
                [
                    'form_params' => [
                        'uso'       => 'American Kryptos Bank',
                        'monto'     => (float) $cardInfo->monto,
                        'api_token' => env('ATH_TOKEN'),
                    ],
                ]
            );
            $processInATH = \GuzzleHttp\json_decode($response->getBody()->getContents());

            if ($processInATH->status === 'error') {
                $banker->rejectWalletTransaction($holdID, 'ATH Message: ' . $processInATH->card);

                Session::flash(
                    'error',
                    'El monto no coincide con el valor de la tarjeta de regalo o la tarjeta de regalo es inválida.' .
                    ' Trata de nuevo.'
                );

                return redirect('/wallets/charge?identity=' . $inputs['identity']);
            }

            $banker->payWalletTransaction($holdID, $inputs['pay_method']);
            Session::flash('success', 'Su recarga se ha completado con éxito.');

            return Redirect::to('/wallets/details/' . $holdID);
        }

        if ($inputs['paymentMethod'] !== 'card' && $inputs['paymentMethod'] !== 'ath_prepaid'
            && $inputs['paymentMethod'] !== 'cash') {
            //Create hold Wallet Transaction
            $holdID = $banker->holdWalletCredits(
                $userWallet['id'],
                $controlToReceive,
                $userWallet['currency'],
                $inputs['paymentMethod'],
                null,
                1,
                null,
                null,
                $inputs
            );

            //END
            Session::flash('success', 'Su recarga se ha completado con éxito.');

            return Redirect::to('/wallets/details/' . $holdID);
        }

        //END TEMP
        /* Session::flash('error', 'Éste método no está habilitado por ahora.');

        return redirect('/wallets/charge?identity=' . $inputs['identity']); */

        $holdTransaction = $banker->holdWalletCredits(
            $userWallet['id'],
            $controlToReceive,
            $userWallet['currency'],
            $inputs['paymentMethod'],
            null,
            1,
            null,
            null,
            $inputs,
            true
        );

        /** @var integer|null $operatorID */
        $operatorID = Banker::assignWalletsOperator($holdTransaction);

        Pusher::trigger(
            'wallets-channel',
            'transaction-order',
            ['message' => Auth::user()->name . ' has created a new charge order.']
        );

        if ($operatorID !== null) {
            Pusher::trigger(
                'wallets-operator-' . $operatorID . '-channel',
                'transaction-order',
                [
                    'message' => 'Se le ha asignado una operación de cambio. Tracking ID: ' .
                    $holdTransaction->tracking_id,
                ]);
        }

//        $data2 = [
        //            'merchant' => Auth::user()->name,
        //            'url'      => URL::to('/user-wallets-transactions/transaction/' . $holdTransaction->id),
        //        ];

//        Mail::to([
        //            'gdf@americankryptosbank.com',
        //            'gsq@americankryptosbank.com'
        //        ])->send(new NewTransaction($data2));

        //END
        Session::flash('success', 'Su recarga se ha completado con éxito.');

        return Redirect::to('/wallets/transaction/' . $holdTransaction->id);
    }

    public function getWalletTransaction($id)
    {
        $transaction = UserWalletsTransactions::find($id);

        if (Auth::user()->id !== $transaction->user_id) {
            return \redirect('/');
        }

        $account = BankAccount::where(['wallet_transaction_id' => $id, 'canceled' => null])->orderBy('id',
            'ASC')->get();

        return view('services.wallet-transaction')->with(compact('transaction', 'account'));
    }

    /**
     * @param int $id
     *
     * @return \Illuminate\Contracts\View\Factory|RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function getWalletTransactionDetails(int $id)
    {
        $transaction = UserWalletsTransactions::where(['id' => $id])
            ->with('relatedTransaction')
            ->with([
                'relatedWalletTransaction' => static function ($query) {
                    $query->with('relatedTransaction');
                },
            ])
            ->first();

        $notes = StatusNotesIncoming::with(
            'subject',
            'traderProfile'
        )->where('client_id', '=', $transaction->user_id)->get();

        if (Auth::user()->id !== $transaction['user_id']) {
            return \redirect('/');
        }

        $genericPaymentObject = GenericPaymentsMethods::where('metadata', 'LIKE', '%' .
            $transaction->payment_way . '%')
            ->first();

        if (isset($transaction->metadata['related_email'])) {
            $transaction->related_user = User::where('email', $transaction->metadata['related_email'])->first();
        }

        if ($transaction->payment_way === 'zelle') {
            return view('services.wallet-transaction-details-zeller')
                ->with(compact(
                    'transaction',
                    'genericPaymentObject',
                    'notes'
                ));
        } else {
            return view('services.wallet-transaction-details')
                ->with(compact(
                    'transaction',
                    'genericPaymentObject',
                    'notes'
                ));
        }

    }

    /**
     * @return RedirectResponse
     */
    public function walletChargeReportPayment(Request $request): RedirectResponse
    {

        App::setLocale('es');

        $request->validate([

            'comprobante_de_pago'    => 'required|image|mimes:jpeg,png,jpg,gif,svg,pdf|max:4000',
            'terminos'               => 'accepted',
            'numero_de_confirmacion' => 'required',

        ]);

        $inputs = request()->all();

        if (isset($inputs['comprobante_de_pago'])) {
            $file_name = strtolower(str_replace(' ',
                '',
                $inputs['comprobante_de_pago']->getClientOriginalName()
            ));
            $file_name = preg_replace('/[^A-Za-z0-9 _ .-]/', '', $file_name);

            $inputs['comprobante_de_pago']->move(
                base_path() . '/public/img/user-exchange-transactions-images/' . $inputs['id'] . '/' . '/',
                $file_name
            );

            $userWalletTransaction                  = UserWalletsTransactions::find($inputs['id']);
            $userWalletTransaction->payment_support = '/img/user-exchange-transactions-images/' .
                $inputs['id'] . '/' . $file_name;

            $userWalletTransaction->notes = $request->numero_de_confirmacion ?? null;

            if (isset($inputs['amaz_tracking_n'])) {
                $metaData                        = $userWalletTransaction->metadata;
                $metaData['amaz_tracking_n']     = $inputs['amaz_tracking_n'];
                $userWalletTransaction->metadata = $metaData;
            }

            $userWalletTransaction->save();
        }
        return redirect('wallets/details-fin/' . $userWalletTransaction->id);

        //return Redirect::to('/wallets/details/' . $inputs['id']);
    }

    public function walletChargeReportPaymentFin($id)
    {
        $userExchangeTransaction = UserWalletsTransactions::find($id);

        $data['pago'] = $userExchangeTransaction;
        return view('services.wallet-transaction-fin', $data);
    }

    public function getMessages($order_id)
    {
        $order = UserWalletsTransactions::find($order_id);
        $user  = User::find($order->user_id);

        return [
            'messages' => WalletTransactionMessage::where('order_id', $order_id)
                ->with('userAccount')
                ->get(),
            'user'     => $user,
        ];
    }

    /**
     * @param $order_id
     *
     * @return array
     */
    public function postCreateMessage($order_id): array
    {
        $inputs    = request()->all();
        $file_name = '';

        if ($inputs['message'] === null) {
            $inputs['message'] = '';
        }

        if (isset($inputs['file'])) {
            $file_name = strtolower(str_replace(' ', '', $inputs['file']->getClientOriginalName()));
            $file_name = preg_replace('/[^A-Za-z0-9 _ .-]/', '', $file_name);

            $inputs['file']->move(base_path() . '/public/img/orders-imgs/' . $order_id . '/' . '/', $file_name);

            if (isset($inputs['bankAccountID'])) {
                $bankAccount                    = BankAccount::where(['id' => $inputs['bankAccountID']])->first();
                $bankAccount->confirmation_file = '/img/orders-imgs/' . $order_id . '/' . $file_name;
                $bankAccount->payed             = true;
                $bankAccount->payed_at          = Carbon::now()->format('Y-m-d H:i:s');
                $bankAccount->save();

                //Temp Message
                $inputs['message'] = 'Se ha enviado un comprobante de pago para la cuenta: ' .
                $bankAccount->account_number . ' del banco: ' . $bankAccount->bank_name .
                    '. Se ha marcado como pagada.';
            }
        }

        WalletTransactionMessage::create([
            'order_id'        => $order_id,
            'msg'             => $inputs['message'],
            'sender_id'       => Auth::user()->id,
            'attachment_name' => $file_name,
        ]);

        //When the user write
        if (isset($inputs['operatorID']) && $inputs['operatorID'] !== null) {
            Pusher::trigger('wallets-operator-' . $inputs['operatorID'] . '-channel',
                'notification-chat-' . $order_id,
                [
                    'message'   => Auth::user()->name . ' has sent you a message.',
                    'sender_id' => Auth::user()->id,
                    'user_role' => Auth::user()->role_id,
                ]
            );
            //When operator write
        } else {
            Pusher::trigger('wallets-channel',
                'notification-chat-' . $order_id,
                [
                    'message'   => 'Le han envíado un mensaje.',
                    'sender_id' => Auth::user()->id,
                    'user_role' => Auth::user()->role_id,
                ]
            );
        }

        return [
            'success',
            WalletTransactionMessage::where('order_id', $order_id)
                ->with('userAccount')
                ->get(),
        ];
    }

    public function getQueue($id)
    {
        $ordersInQueue = UserWalletsTransactions::where(
            [
                'attended_by' => null,
                'payment_way' => 'cash_deposit',
                'status'      => 0,
            ]
        )
            ->orderBy('id', 'DESC')
            ->get()
            ->keyBy('id')
            ->toArray();

        if (!isset($ordersInQueue[$id])) {
            return 0;
        }

        $positionInQueue = array_search($id, array_keys($ordersInQueue));
        $positionInQueue = count($ordersInQueue) - $positionInQueue;

        return $positionInQueue;
    }

    public function getSend()
    {
        $inputs      = \request()->all();
        $userWallets = CurrencyWallet::where([
            'user_id'  => Auth::user()->id,
            'status'   => 1,
            'currency' => 'USD',
        ])
            ->get()
            ->toArray();

        if (!isset($inputs['identity'])) {
            Session::flash('error', 'No se ha indicado un wallet');

            return redirect('/wallets');
        }

        $userWallet = null;
        foreach ($userWallets as $wallet) {
            if ($wallet['hash'] === $inputs['identity']) {
                $userWallet = $wallet;
            }
        }

        if ($userWallet) {
            return view('services.send')->with(compact(['userWallet', 'userWallets']));
        }

        Session::flash('error', 'La wallet está inhabilitada');

        return redirect('/wallets');
    }

    public function getTransfer()
    {
        if (Auth::user()->personProfile->approval_status !== 2) {
            return Redirect::to('/user-info');
        }

        $activeTransaction = UserWalletsTransactions::where(
            [
                'user_id'     => Auth::user()->id,
                'status'      => 1,
                'type'        => 3,
                'payment_way' => 'cash',
            ]
        )->orderBy('created_at', 'DESC')->first();

        if ($activeTransaction) {
            return Redirect::to('/wallets/transaction/' . $activeTransaction->id)->with(
                'error',
                'Posee una transacción activa.'
            );
        }

        $inputs      = \request()->all();

        if (!isset($inputs['identity'])) {
            Session::flash('error', 'No se ha indicado un wallet');

            return redirect('/wallets');
        }

        $userWallets = CurrencyWallet::where([
            'user_id'  => Auth::user()->id,
            'status'   => 1,
            'currency' => 'USD',
        ])
            ->get()
            ->toArray();

        if (empty($userWallets)) {
            $banker = new Banker;
            $userWallets = [$banker->createWallet('USD', null, true)->toArray()];
        }

        $userWallet = null;
        foreach ($userWallets as $wallet) {
            if ($wallet['hash'] === $inputs['identity']) {
                $userWallet = $wallet;
            }
        }

        if ($userWallet) {
            return view('services.transfer')->with(compact(['userWallet', 'userWallets']));
        }

        Session::flash('error', 'La wallet está inhabilitada');

        return redirect('/wallets');
    }

    /**
     *
     */
    public function postWalletTransfer()
    {
        $inputs = \request()->all();
        //1st Find User
        $destinationUserId = User::where('email', $inputs['receiver-email'])
            ->select('id')
            ->first();

        if ($destinationUserId === null) {
            Session::flash('error', 'El usuario no existe en el sistema');

            return redirect('/wallets/transfer?identity=' . $inputs['identity']);
        }

        $banker = new Banker;
        //Create Wallet or get the existing one
        if ($inputs['sending-currency'] === 'EUR' || $inputs['sending-currency'] === 'GBP') {
            $walletID = $banker->createWallet($inputs['sending-currency'], $destinationUserId->id);
        } else {
            $walletID = $banker->createWallet('USD', $destinationUserId->id);
        }

        $userWallet = CurrencyWallet::where([
            'user_id' => Auth::user()->id,
            'hash'    => $inputs['identity'],
        ])
            ->first()
            ->toArray();

        $operationID = $banker->transferBetweenWallets(
            $userWallet,
            $walletID,
            $inputs['sending-amount'],
            $destinationUserId->id,
            $inputs['receiver-email'],
            Auth::user()->email,
            $inputs['transfer-description'] ?? null
        );

        if ($operationID) {
            Session::flash(
                'success',
                'La operación se ha completado con éxito.'
            );

            $destinationUser = User::where('email', $inputs['receiver-email'])
                ->first();

            $data = [
                'sender'   => Auth::user()->name,
                'receiver' => $destinationUser->name,
                'amount'   => $inputs['sending-amount'],
                'currency' => $inputs['sending-currency'],
                'url'      => URL::to('/'),
            ];

            Mail::to([
                Auth::user()->email,
            ])->send(new SendWalletsMoneyMail($data));

            Mail::to([
                $inputs['receiver-email'],
            ])->send(new ReceiveWalletsMoneyMail($data));

            return redirect('/wallets/details/' . $operationID);
        }

        Session::flash(
            'error',
            'El monto envíado no es válido o existe un problema con la operación. Revise e intente nuevamente'
        );

        return redirect('/wallets/transfer?identity=' . $inputs['identity']);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function getWithdraw()
    {
        if (Auth::user()->personProfile->approval_status !== 2) {
            return Redirect::to('/user-info');
        }

        $activeTransaction = UserWalletsTransactions::where(
            [
                'user_id'     => Auth::user()->id,
                'status'      => 1,
                'type'        => 3,
                'payment_way' => 'cash',
            ]
        )->orderBy('created_at', 'DESC')->first();

        if ($activeTransaction) {
            return Redirect::to('/wallets/transaction/' . $activeTransaction->id)->with(
                'error',
                'Posee una transacción activa.'
            );
        }

        $inputs      = \request()->all();

        if (!isset($inputs['identity'])) {
            Session::flash('error', 'No se ha indicado un wallet');

            return redirect('/wallets');
        }

        $userWallets = CurrencyWallet::where([
            'user_id'  => Auth::user()->id,
            'status'   => 1,
            'currency' => 'USD',
        ])
            ->get()
            ->toArray();

        if (empty($userWallets)) {
            $banker = new Banker;
            $userWallets = [$banker->createWallet('USD', null, true)->toArray()];
        }

        $userWallet = null;
        foreach ($userWallets as $wallet) {
            if ($wallet['hash'] === $inputs['identity']) {
                $userWallet = $wallet;
            }
        }

        if ($userWallet) {
            return view('services.withdraw')->with(compact(['userWallet', 'userWallets']));
        }

        Session::flash('error', 'La wallet está inhabilitada');

        return redirect('/wallets');
    }

    public function postWalletWithdraw()
    {
        $inputs     = \request()->all();
        $banker     = new Banker;
        $userWallet = CurrencyWallet::where([
            'user_id' => Auth::user()->id,
            'hash'    => $inputs['identity'],
        ])
            ->first()
            ->toArray();

        if (!$banker->verifyOutgoingAmount($userWallet, $inputs['sending-amount'])) {
            Session::flash(
                'error',
                'El monto envíado no es válido o existe un problema con la operación. Revise e intente nuevamente'
            );

            return redirect('/wallets/withdraw?identity=' . $inputs['identity']);
        }

        //Normalize Inputs for Hold
        $inputs['sender_country']     = 'United States';
        $inputs['to_send']            = (float) str_replace(',', '', $inputs['sending-amount']);
        $inputs['amount']             = $inputs['to_send'];
        $inputs['pay_method']         = 'userWallet';
        $inputs['pay_method_country'] = 'US';
        $inputs['tracking_id']        = uniqid('', false);
        //End Normalize Inputs for Hold

        $exchangePriceData = Banker::walletsGetPrice($inputs, 'withdraw');
        $controlToReceive  = round($inputs['to_send'] * $exchangePriceData[0], 2);
        $to_receive_float  = (float) str_replace(',', '', $inputs['to_receive']);

        if ((string) $to_receive_float !== (string) $controlToReceive) {
            return Redirect::back()->with('error',
                'El monto a recibir es incorrecto. Por favor, revise e intente de nuevamente');
        }

        $userNewTransaction                      = new UserExchangeTransactions();
        $userNewTransaction->exchange_rate_id    = $exchangePriceData[2];
        $userNewTransaction->tracking_id         = $inputs['tracking_id'];
        $userNewTransaction->user_id             = Auth::user()->id;
        $userNewTransaction->destination_account = $inputs['destination_id'];

        //JSON registration
        $userNewTransaction->destination_account_json = DestinationAccount::find($inputs['destination_id']);
        //END

        $userNewTransaction->sender_fiat        = $inputs['sender'];
        $userNewTransaction->sender_fiat_amount = round(
            (float) str_replace(',', '', $inputs['sending-amount']),
            2
        );
        $userNewTransaction->receiver_fiat        = $inputs['receiver'];
        $userNewTransaction->receiver_fiat_amount = round(
            (float) str_replace(',', '', $inputs['to_receive']),
            2
        );
        $userNewTransaction->exchange_rate  = round($exchangePriceData[0], 2);
        $userNewTransaction->fee_at_moment  = $exchangePriceData[3];
        $inputs['sending-amount']           = (float) str_replace(',', '', $inputs['sending-amount']);
        $userNewTransaction->payment_way    = 'userWallet';
        $userNewTransaction->metadata       = ['wallet_hash' => $inputs['identity']];
        $userNewTransaction->payment_source = 'User Wallet';
        $userNewTransaction->status         = 0;
        $userNewTransaction->is_revised     = 0;
        $userNewTransaction->is_payed       = 1;
        $userNewTransaction->payed_at       = Carbon::now()->toDateTimeString();
        $userNewTransaction->payed_by       = 'User Wallet: ' . $inputs['identity'];

        if ($inputs['sender'] !== 'USD') {
            $exchangeRates = HelpersController::getExchangeRates();
            $usdToCharge   = $inputs['sending-amount'] / $exchangeRates['rates'][$inputs['sender']];
        } else {
            $usdToCharge = $inputs['sending-amount'];
        }

        //Create hold Wallet Transaction
        $walletTransactionID = $banker->holdWalletCredits(
            $userWallet['id'],
            $usdToCharge,
            $inputs['sender'],
            $userNewTransaction->payment_way,
            null,
            2,
            'Wallets System',
            null,
            $inputs
        );

        $userNewTransaction->wallet_transaction_id = $walletTransactionID;
        $userNewTransaction->save();

        Session::flash(
            'success',
            'La operación se ha completado con éxito.'
        );

        Pusher::trigger(
            'wallets-channel',
            'transaction-order',
            ['message' => Auth::user()->name . ' has created a new withdraw order.']
        );

        return redirect('/transaction-success/' . $userNewTransaction->id);
    }

    public function getIncomingWalletTransactions()
    {
        $transactions = UserWalletsTransactions::where([
            'exchange_related' => 0,
            'purpose'          => 1,
        ])
            ->where('status', '!=', 3)
            ->where('status', '!=', 4)
            ->orderBy('id', 'DESC')
            ->with('userAccount')
            ->with('destinationAccount')
            ->paginate(10);

        $purpose = 1;

        return view('transactions.wallet-transactions-list')->with(compact('transactions', 'purpose'));
    }

    public function getOutgoingWalletTransactions()
    {
        $transactions = UserWalletsTransactions::where([
            'exchange_related' => 0,
            'purpose'          => 2,
        ])
            ->where('status', '!=', 3)
            ->where('status', '!=', 4)
            ->orderBy('id', 'DESC')
            ->with('userAccount')
            ->with('destinationAccount')
            ->paginate(10);

        $purpose = 2;

        return view('transactions.wallet-transactions-list')->with(compact('transactions', 'purpose'));
    }

    public function transactionsPagination()
    {
        $inputs = request();
        //return dd($inputs->all());
        $currentPage = $inputs->page;

        Paginator::currentPageResolver(function () use ($currentPage) {
            return $currentPage;
        });

        if ((int) $inputs['order_by_trader'] === 1) {
            $transactions = UserWalletsTransactions::orderBy('trader_id', 'ASC')->whereNotNull('trader_id');
        } else {
            $transactions = UserWalletsTransactions::orderBy('id', 'DESC');
        }

        // $transactions->where('created_at', '<=', $inputs['end_date'])->where('created_at', '>=', $inputs['start_date']);

        if ($inputs['sender'] === 'All' && $inputs['receiver'] === 'All') {
            $transactions = $transactions->with('userAccount')
                ->with('destinationAccount');
        } elseif ($inputs['sender'] !== 'All' && $inputs['receiver'] === 'All') {
            $transactions = $transactions->where('sender_fiat', $inputs['sender'])
                ->with('userAccount')
                ->with('destinationAccount');
        } elseif ($inputs['sender'] === 'All' && $inputs['receiver'] !== 'All') {
            $transactions = $transactions->where('receiver_fiat', $inputs['receiver'])
                ->with('userAccount')
                ->with('destinationAccount');
        } else {
            $transactions = $transactions->where('sender_fiat', $inputs['sender'])
                ->where('receiver_fiat', $inputs['receiver'])
                ->with('userAccount')
                ->with('destinationAccount');
        }
//        dd($inputs['transactions_status']);
        if ($inputs['transactions_status'] !== 'All') {
            $transactions = $transactions->where('status', (int) $inputs['transactions_status']);
        }

        if (isset($inputs['user_name']) && !is_null($inputs['user_name'])) {
            $transactions->whereHas('merchant', function ($q) use ($inputs) {
                $q->where('name', 'like', '%' . $inputs['user_name'] . '%');
            });
        }

        if (isset($inputs['user_email']) && !is_null($inputs['user_email'])) {
            $transactions->whereHas('merchant', function ($q) use ($inputs) {
                $q->where('email', 'like', '%' . $inputs['user_email'] . '%');
            });
        }

        return $transactions->where([
            'purpose'          => $inputs['purpose'],
            'exchange_related' => 0,
        ])
            ->with('destinationAccount', 'userAccount')
            ->paginate(10);
    }

    /**
     * @param $id
     *
     * @return array
     */
    public function assignTransaction($id)
    {
        $transaction = UserWalletsTransactions::find($id);
        // unset($transaction['edtDate']);

//        if (Auth::user()->assignedWalletsTransactions->whereNotIn('status', 0)->count() >= 5) {
        //            return [
        //                'status' => 'error',
        //                'msg'    => 'Ya has alcanzado el limite de transacciones asignadas.'
        //            ];
        //        }

        if (!isset($transaction->trader_id)) {

            $transaction->trader_id   = Auth::user()->id;
            $transaction->trader_info = [
                'name'  => Auth::user()->name,
                'id'    => Auth::user()->id,
                'email' => Auth::user()->email,
            ];
            $transaction->save();

            return [
                'status' => 'success',
                'msg'    => 'Se te ha asignado una nueva transaccion.',
            ];

        }

        return [
            'status' => 'error',
            'msg'    => 'Esta transaccion ya ha sido asignada a alguien mas.',
        ];
    }

    public function getTransactionDetails($id)
    {
        $transaction = UserWalletsTransactions::with(
            'merchant',
            'messages',
            'destinationAccount'
        )->find($id);

        $transactionOfToday = UserWalletsTransactions::where('user_id',  $transaction->user_id )->whereDate('created_at',Carbon::today()->toDateString())->count();
        $notes = StatusNotesIncoming::with(
            'subject',
            'traderProfile'
        )->where('client_id', '=', $transaction->user_id)->get();

        $subjects                    = SubjectsStatusIncoming::orderBy('id', 'DESC')->get();
        $user                        = Auth::user();
        $typeString                  = $transaction->purpose === 1 ? 'charge' : 'withdraw';
        $transaction['exchangeRate'] = Banker::walletsGetPrice(
            [
                'sender'           => $transaction->sender_fiat,
                'sender_country'   => 'United States',
                'amount'           => 150,
                'receiver'         => 'VES',
                'receiver_country' => 'Venezuela',
            ],
            $typeString
        );

        if ($transaction->purpose === 1) {
            return view('transactions.incoming-wallet-transaction')->with(compact('transaction', 'user', 'subjects', 'notes','transactionOfToday'));
        }

        $exchangeTransaction = UserExchangeTransactions::where(['tracking_id' => $transaction->tracking_id])
            ->first();

        return view('transactions.outgoing-wallet-transaction')->with(
            compact('transaction', 'exchangeTransaction', 'user')
        );
    }

    public function setNewNoteStatus(Request $request, $id)
    {
        $note                 = new StatusNotesIncoming();
        $inputs               = request()->all();
        $user                 = Auth::user();
        $banker               = new Banker;
        $transaction          = UserWalletsTransactions::find($id);
        $note->status         = $inputs['status'];
        $note->subject_id     = $inputs['subjects'];
        $note->msg            = $inputs['status-message'];
        $note->client_id      = $transaction->user_id;
        $note->transaction_id = $transaction->id;
        $note->ip             = $request->ip();
        $note->trader_id      = $user->id;

        if ((int) $inputs['status'] === 2 && (int) $inputs['status'] !== $transaction->status) {
            $banker->removeHold($transaction, 2);

            if ($transaction->purpose === 1) {
                $banker->rechargeWalletFromHold($transaction);
            } elseif ($transaction->purpose === 2) {
                $banker->withdrawWalletFromHold($transaction);
            }

            $transaction->approved_at = Carbon::now();
            $transaction->approved_by = Auth::user()->name;
        }

        if ((int) $inputs['status'] !== $transaction->status &&
            ((int) $inputs['status'] === 3 || (int) $inputs['status'] === 4 || (int) $inputs['status'] === 5)) {
            $banker->removeHold($transaction, 3);

            $transaction->failed_at = Carbon::now();
            $transaction->failed_by = Auth::user()->name;
        }

        if ((int) $inputs['status'] !== $transaction->status &&
            ((int) $inputs['status'] === 1 || (int) $inputs['status'] === 6)) {
            $transaction->status = (int) $inputs['status'];
        }

        $transaction->save();
        $note->save();
        if ($inputs['status']) {
            //merchant email
            $data2 = [
                'name'           => $transaction->merchant->name,
                'status'         => $transaction->status,
                'msg'            => $note->msg,
                'transaction_id' => $transaction->tracking_id,
                'url'            => URL::to('/transaction-success/' . $transaction->id . '#' . $note->id),
            ];
            Mail::to([$transaction->merchant->email])->send(new TransactionStatusNoteIncoming($data2));

            // return view('emails.transaction-status-note-incoming')->with(compact('data'));
        }

        return Redirect::to('/wallets/transaction-details/' . $transaction->id)->with('success',
            'Customer transaction has been updated.');
    }

    public function newSubject()
    {

        $inputs           = request()->all();
        $subject          = new SubjectsStatusIncoming();
        $subject->status  = $inputs['status'];
        $subject->subject = $inputs['subject'];
        $subject->save();

        return Redirect::back()->with('success', 'Se agrego el asunto');

    }

    public function editSubject($id)
    {

        $inputs           = request()->all();
        $subject          = SubjectsStatusIncoming::find($id);
        $subject->subject = $inputs['subject'];
        $subject->save();

        return Redirect::back()->with('success', 'Se actualizo el asunto');
    }

    public function deleteSubject($id)
    {

        $subject = SubjectsStatusIncoming::find($id);
        $subject->delete();
        return Redirect::back()->with('success', 'Se elimino el asunto');
    }

    public function byCategory($id)
    {
        return SubjectsStatusIncoming::where('status', '=', $id)->get();
    }

    public function markAsPayed($id): RedirectResponse
    {
        $this->changePayed($id);
        $transaction = UserWalletsTransactions::find($id);

        $paymentMethods = [
            'cash'         => 'Pago en Efectivo',
            'Stripe'       => 'Tarjeta de Crédito o Débito',
            'QuickBook'    => 'Tarjeta de Crédito o Débito QB',
            'zelle'        => 'Zelle',
            'venmo'        => 'Venmo',
            'cashapp'      => 'Cash App',
            'payoneer'     => 'Payoneer',
            'popmoney'     => 'PopMoney',
            'pandco'       => 'Pandco',
            'amaz_prepaid' => 'Tarjeta prepagada VISA Amazon',
        ];

        $banker = new Banker();
        $banker->payWalletTransaction(
            $id,
            $paymentMethods[$transaction->payment_way]
        );

        return Redirect::to('/wallets/transaction-details/' . $id);
    }

    public function unmarkAsPayed($id): RedirectResponse
    {
        $this->changeNotPayed($id);
        $transaction = UserWalletsTransactions::find($id);

        return Redirect::to('/wallets/transaction-details/' . $id);
    }

    private function changePayed(int $id)
    {
        $transaction           = UserWalletsTransactions::find($id);
        $transaction->is_payed = 1;
        $transaction->payed_at = Carbon::now()->format('Y-m-d H:i:s');
        $transaction->payed_by = 'Trader Master: ' . Auth::user()->name;
        $transaction->save();

        return json_encode($transaction);
    }

    private function changeNotPayed(int $id)
    {
        $transaction           = UserWalletsTransactions::find($id);
        $transaction->is_payed = 0;
        $transaction->payed_at = null;
        $transaction->payed_by = null;
        $transaction->save();

        return json_encode($transaction);
    }

    public function updateTransaction($id)
    {
        $inputs      = request()->all();
        $transaction = UserWalletsTransactions::find($id);
        $banker      = new Banker;

        /**
         * If is approved
         */
        if ((int) $inputs['status'] === 2 && (int) $inputs['status'] !== $transaction->status) {
            $banker->removeHold($transaction, 2);

            if ($transaction->purpose === 1) {
                $banker->rechargeWalletFromHold($transaction);
            } elseif ($transaction->purpose === 2) {
                $banker->withdrawWalletFromHold($transaction);
            }

            $transaction->approved_at = Carbon::now();
            $transaction->approved_by = Auth::user()->name;
        }

        if ((int) $inputs['status'] !== $transaction->status &&
            ((int) $inputs['status'] === 3 || (int) $inputs['status'] === 4 || (int) $inputs['status'] === 5)) {
            $banker->removeHold($transaction, 3);

            $transaction->failed_at = Carbon::now();
            $transaction->failed_by = Auth::user()->name;
        }

        if ((int) $inputs['status'] !== $transaction->status &&
            ((int) $inputs['status'] === 1 || (int) $inputs['status'] === 6)) {
            $transaction->status = (int) $inputs['status'];
        }

        $transaction->save();

        //admins email
        //        $data = [
        //            'status'         => $transaction->status,
        //            'transaction_id' => $transaction->tracking_id,
        //            'url'            => URL::to('/exchange-transaction/' . $transaction->id),
        //        ];

//        Mail::to([
        //            'gdf@americankryptosbank.com',
        //            'gsq@americankryptosbank.com'
        //        ])->send(new TransactionStatus($data));

        //merchant email
        $data2 = [
            'name'           => $transaction->merchant->name,
            'status'         => $transaction->status,
            'transaction_id' => $transaction->tracking_id,
            'url'            => URL::to('/transaction-success/' . $transaction->id),
        ];

        Mail::to([$transaction->merchant->email])->send(new MerchantTransactionStatus2($data2));

        if ($transaction->payment_way === 'cash' && (int) $inputs['status'] !== 0 && (int) $inputs['status'] !== 1) {
            Pusher::trigger('wallets-channel',
                'queue-event',
                [
                    'message'        => 'Transaction with id = ' . $transaction->id . ' has been approved.',
                    'order_id'       => $transaction->id,
                    'order_finished' => true,
                    //Many operations out of the queue
                    'many_out'       => 1,
                    'operator_id'    => null,
                ]
            );
        }

        return Redirect::to('/wallets/transaction-details/' . $transaction->id)->with('success',
            'Customer transaction has been updated.');
    }

    public function getWalletsUsers()
    {

        $userProfile = UserPersonProfile::where('approval_status', '!=', 3)
            ->where('approval_status', '!=', 0)
            ->pluck('user_id');

        $users = User::whereIn('id', $userProfile)
            ->with('personProfile')
            ->where('role_id', 5)
            ->with([
                'currencyWallet' => static function ($query) {
                    $query->where([
                        'status'   => 1,
                        'currency' => 'USD',
                    ]);
                },
            ])->orderBy('name', 'ASC');

        $inputs = request()->all();

        if (isset($inputs['user-name'])) {

            $userName = $inputs['user-name'];

            if ($userName) {

                $users = $users->where('name', 'LIKE', '%' . $userName . '%');
            }

        }

        if (isset($inputs['user-lastname'])) {

            $userLastname = $inputs['user-lastname'];

            if ($userLastname) {

                $users = $users->where('name', 'LIKE', '%' . $userLastname . '%');
            }

        }

        $userExchangeTransactions = UserExchangeTransactions::whereIn('user_id', $userProfile);
        /*
        Ordenar trasacciones de mayor a menor
        de menor a mayor
        por rango
         */
        $users = $users->paginate(30);

        return view('services.wallets-users')->with(compact(
            'users',
            'userExchangeTransactions'
        ));

    }

    public function getWalletsDashboard()
    {
        $data              = [];
        $banker            = new Banker();
        $data['totals']    = $banker::getTotalBalanceUserWallets(null, '2019-08-20 00:00:00');
        $data['movements'] = $banker::getWalletsBalanceMovements('2019-08-20 00:00:00');

        return view('transactions.wallets-dashboard')->with(compact('data'));
    }

    /**
     * Get specific user orders.
     *
     * @return mixed
     */
    public function getOrders()
    {
        $userTransactions = UserWalletsTransactions::where(
            [
                'status'      => 1,
                'type'        => 3,
                'purpose'     => 1,
                'payment_way' => 'cash',
            ]
        );

        if (Auth::user()->role_id !== 1) {
            $userTransactions = $userTransactions->where(['attended_by' => Auth::user()->id]);
        }

        $userTransactions = $userTransactions->with('masterAccount')
            ->with([
                'merchant' => static function ($query) {
                    $query->with('personProfile');
                },
            ])
            ->with('operator')
            ->get();

        return $userTransactions;
    }

    /**
     * @param $id
     *
     * @return mixed
     */
    public function getBankAccountsStatus($id)
    {
        $nowUTC       = Carbon::now()->format('Y-m-d H:i:s');
        $bankAccounts = BankAccount::where(
            [
                'order_id' => $id,
            ]
        )
            ->orderBy('id', 'ASC')
            ->get();
        $returnAccounts = [];

        foreach ($bankAccounts as $account) {
            if ($account->limit_date <= $nowUTC) {
                if ($account->canceled === null && $account->failed === null && $account->payed === null) {
                    $account->failed    = 1;
                    $account->failed_at = $nowUTC;
                }

                if ($account->payed === null) {
                    $returnAccounts[$account->id] = 2;
                }

                if ($account->payed === 1) {
                    $returnAccounts[$account->id] = 1;
                }

                if ($account->canceled === 1) {
                    $returnAccounts[$account->id] = 3;
                }
            } else {
                if ($account->payed === 1) {
                    $returnAccounts[$account->id] = 1;
                }

                if ($account->canceled === 1) {
                    $returnAccounts[$account->id] = 3;
                }

                if ($account->canceled === null && $account->payed === null && $account->failed === null) {
                    $returnAccounts[$account->id] = 0;
                }
            }

            $account->save();
        }

        return $returnAccounts;
    }

    /**
     * @return array
     */
    public function cancelBankAccount(): array
    {
        $inputs                   = request()->all();
        $bankAccount              = BankAccount::where('id', $inputs['account_id'])->first();
        $bankAccount->canceled    = 1;
        $bankAccount->canceled_at = Carbon::now()->format('Y-m-d H:i:s');
        $bankAccount->save();

        $transactionOrderMessages = WalletTransactionMessage::where('order_id', $bankAccount->wallet_transaction_id)
            ->with('userAccount')
            ->get();

        foreach ($transactionOrderMessages as $transactionOrderMessage) {
            if ($transactionOrderMessage->json_data['bank_account_db_id'] === $bankAccount->id) {
                $jsonData                           = $transactionOrderMessage->json_data;
                $jsonData['canceled']               = 1;
                $transactionOrderMessage->json_data = $jsonData;
                $transactionOrderMessage->save();
            }
        }

        Pusher::trigger('wallets-channel',
            'cancel-account-' . $bankAccount->wallet_transaction_id,
            [
                'cancelled_by' => Auth::user()->id . ' ' . Auth::user()->name,
            ]
        );

        return ['success', $transactionOrderMessages];
    }

    /**
     * @param $id
     *
     * @return array
     */
    public function createPaymentMessage($id): array
    {
        $inputs    = request()->all();
        $limitDate = Carbon::now()->addMinutes($inputs['available_minutes'])->format('Y-m-d H:i:s');

        $bankAccount = BankAccount::create([
            'order_id'              => 0,
            'wallet_transaction_id' => (int) $id,
            'bank_name'             => $inputs['bankname'],
            'account_type'          => $inputs['account_type'],
            'account_number'        => $inputs['account_number'],
            'account_owner'         => $inputs['account_owner'],
            'id_number'             => $inputs['id_number'],
            'fiat_amount'           => $inputs['fiat_amount'],
            'limit_date'            => $limitDate,
        ]);

        WalletTransactionMessage::create([
            'order_id'  => $id,
            'sender_id' => Auth::user()->id,
            'msg'       => '',
            'type'      => 2,
            'json_data' => [
                'bank_account_db_id' => $bankAccount->id,
                'bank_name'          => $inputs['bankname'] . ' ' . $inputs['account_type'],
                'account_number'     => $inputs['account_number'],
                'account_owner'      => $inputs['account_owner'],
                'id_number'          => $inputs['id_number'],
                'fiat_amount'        => $inputs['fiat_amount'],
                'available_minutes'  => $limitDate,
            ],
        ]);

        Pusher::trigger('wallets-channel', 'bank-account-' . $id, []);

        Pusher::trigger('wallets-channel', 'notification-chat-' . $id,
            ['message' => 'Le han envíado un mensaje.', 'sender_id' => Auth::user()->id]);

        return [
            'success',
            WalletTransactionMessage::where('order_id', $id)
                ->with('userAccount')
                ->get(),
        ];
    }
}
