<?php

namespace App\Http\Controllers;

use App\Akb\Banker;
use App\Banco;
use App\IncomingBtc;
use App\OutgoingBtc;
use App\UserExchangeTransactions;
use App\SubjectsStatus;
use App\StatusNotes;
use App\UserWalletsTransactions;

use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\TransactionOrderMessage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Pagination\Paginator;
use App\TransactionOrder;
use Illuminate\Support\Facades\Mail;
use App\BankAccount;
use App\User;
use App\ExchangePaymentData;
use App\Lote;
use App\Lotedetalle;
use App\Mail\TransactionStatus;
use App\Mail\MerchantTransactionStatus;
use App\Mail\TransactionStatusNote;
use App\Mail\TransactionCompleted;
use App\Mail\PaymentFile;
use App\Movimiento;
use App\Transaction;
use URL;

use Pusher\Laravel\Facades\Pusher;
use Storage;

class TransactionOrderController extends Controller
{
    public function getMessages($order_id)
    {
        $order = UserExchangeTransactions::find($order_id);
        $user  = User::find($order->user_id);

        return [
            'messages' => TransactionOrderMessage::where('order_id', $order_id)
                ->with('userAccount')
                ->get(),
            'user'     => $user
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

        TransactionOrderMessage::create([
            'order_id'        => $order_id,
            'msg'             => $inputs['message'],
            'sender_id'       => Auth::user()->id,
            'attachment_name' => $file_name
        ]);

        //When the user write
        if (isset($inputs['operatorID']) && $inputs['operatorID'] !== null) {
            Pusher::trigger(
                'operator-' . $inputs['operatorID'] . '-channel',
                'notification-chat-' . $order_id,
                [
                    'message'   => Auth::user()->name . ' has sent you a message.',
                    'sender_id' => Auth::user()->id,
                    'user_role' => Auth::user()->role_id,
                ]
            );
            //When operator write
        } else {
            Pusher::trigger(
                'transactions-channel',
                'notification-chat-' . $order_id,
                [
                    'message'   => 'Le han envíado un mensaje.',
                    'sender_id' => Auth::user()->id,
                    'user_role' => Auth::user()->role_id,
                ]
            );
        }

        Pusher::trigger(
            'admins-super-channel',
            'notification-chat-' . $order_id,
            [
                'message'   => Auth::user()->name . ' has sent you a message.',
                'sender_id' => Auth::user()->id,
                'user_role' => Auth::user()->role_id,
            ]
        );


        return [
            'success',
            TransactionOrderMessage::where('order_id', $order_id)
                ->with('userAccount')
                ->get()
        ];
    }

    /**
     * Get specific user orders.
     *
     * @return mixed
     */
    public function getOrders()
    {
        $userTransactions = UserExchangeTransactions::where(
            [
                'status'      => 0,
                'payment_way' => 'cash_deposit'
            ]
        );

        if (Auth::user()->role_id !== 1 && Auth::user()->role_id !== 6) {
            $userTransactions = $userTransactions->where(['attended_by' => Auth::user()->id]);
        }

        $userTransactions = $userTransactions->with('masterAccount')
            ->with([
                'merchant' => static function ($query) {
                    $query->with('personProfile');
                }
            ])
            ->with('operator')
            ->get();

        return $userTransactions;
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
            'order_id'       => $id,
            'bank_name'      => $inputs['bankname'],
            'account_type'   => $inputs['account_type'],
            'account_number' => $inputs['account_number'],
            'account_owner'  => $inputs['account_owner'],
            'id_number'      => $inputs['id_number'],
            'fiat_amount'    => $inputs['fiat_amount'],
            'limit_date'     => $limitDate
        ]);

        TransactionOrderMessage::create([
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
                'available_minutes'  => $limitDate
            ]
        ]);

        Pusher::trigger('transactions-channel', 'bank-account-' . $id, []);

        Pusher::trigger(
            'transactions-channel',
            'notification-chat-' . $id,
            ['message' => 'Le han envíado un mensaje.', 'sender_id' => Auth::user()->id]
        );

        Pusher::trigger(
            'admins-super-channel',
            'notification-chat-' . $id,
            ['message' => 'Le han envíado un mensaje.', 'sender_id' => Auth::user()->id]
        );

        return [
            'success',
            TransactionOrderMessage::where('order_id', $id)
                ->with('userAccount')
                ->get()
        ];
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

        $transactionOrderMessages = TransactionOrderMessage::where('order_id', $bankAccount->order_id)
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

        Pusher::trigger(
            'transactions-channel',
            'cancel-account-' . $bankAccount->order_id,
            [
                'cancelled_by' => Auth::user()->id . ' ' . Auth::user()->name,
            ]
        );

        return ['success', $transactionOrderMessages];
    }

    /**
     * @param $id
     *
     * @return mixed
     */
    public function getBankAccounts($id)
    {
        return BankAccount::where([
            'order_id' => $id,
            'canceled' => null
        ])
            ->orderBy('id', 'ASC')
            ->get();
    }

    /**
     * @param $id
     *
     * @return mixed
     */
    public function getBankAccountsStatus($id)
    {
        //        $userExchangeTransaction = UserExchangeTransactions::find($id);
        $nowUTC         = Carbon::now()->format('Y-m-d H:i:s');
        $bankAccounts   = BankAccount::where(
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

        //        return [$returnAccounts, $userExchangeTransaction->status];
        return $returnAccounts;
    }

    public function getExchangeTransactions()
    {
        $transactions = UserExchangeTransactions::orderBy('id', 'DESC')
            ->where('status', 0)
            ->with('userAccount')
            ->with('destinationAccount')
            ->paginate(10);

        foreach ($transactions as $key => $transaction) {
            $transactions[$key]['userAccount'] = $transaction['userAccount']->getRating();
        }

        return view('transactions.exchange-transactions-list')->with(compact('transactions'));
    }

    public function getExchange($id)
    {



        $transaction = UserExchangeTransactions::with(
            'merchant',
            'messages',
            'payment',
            'destinationAccount',
            'outgoing',
            'contact'
        )->find($id);

        $transactionOfToday = UserExchangeTransactions::where('user_id',  $transaction->user_id)->whereDate('created_at', Carbon::today()->toDateString())->count();

        $notes = StatusNotes::with(
            'subject',
            'traderProfile'
        )->where('client_id', '=', $transaction->user_id)->get();

        $subjects = SubjectsStatus::orderBy('id', 'DESC')->get();

        if (Auth::user()->id === $transaction->trader_id || Auth::user()->role_id === 1 || Auth::user()->role_id === 2 || Auth::user()->role_id === 8 || Auth::user()->role_id === 3 || Auth::user()->role_id === 6 || Auth::user()->role_id === 7) {
            $transaction['merchant'] = $transaction['merchant']->getRating();

            return view('transactions.edit-exchange')->with(compact('transaction', 'subjects', 'notes', 'transactionOfToday'));
        }

        return Redirect::back()->with('error', 'No tienes permitido el acceso a esta transacción');
    }

    public function setNewNoteStatus(Request $request, $id)
    {
        $note                   = new StatusNotes();
        $inputs                 = request()->all();
        $user                   = Auth::user();
        $transaction            = UserExchangeTransactions::find($id);
        $note->status           = $inputs['status'];
        $note->subject_id       = $inputs['subjects'];
        $note->msg              = $inputs['status-message'];
        $note->client_id        = $transaction->user_id;
        $note->transaction_id   = $transaction->id;
        $note->ip               = $request->ip();
        $note->trader_id        = $user->id;
        $note->is_reply         = 0;
        $transaction->changeStatus((int) $inputs['status']);
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

            Mail::to([$transaction->merchant->email])->send(new TransactionStatusNote($data2));
        }


        return Redirect::to('/exchange-transactions-list/')->with('success', 'Customer transaction has been updated.');
    }

    public function newSubject()
    {

        $inputs           = request()->all();
        $subject          = new SubjectsStatus();
        $subject->status  = $inputs['status'];
        $subject->subject = $inputs['subject'];
        $subject->save();

        return Redirect::back()->with('success', 'Se agrego el asunto');
    }

    public function editSubject($id)
    {

        $inputs           = request()->all();
        $subject          = SubjectsStatus::find($id);
        $subject->subject = $inputs['subject'];
        $subject->save();

        return Redirect::back()->with('success', 'Se actualizo el asunto');
    }

    public function deleteSubject($id)
    {

        $subject = SubjectsStatus::find($id);
        $subject->delete();
        return Redirect::back()->with('success', 'Se elimino el asunto');
    }

    public function byCategory($id)
    {
        return SubjectsStatus::where('status', '=', $id)->get();
    }

    /**
     * @param $id
     *
     * @return RedirectResponse
     */
    public function editExchangeTransaction($id): RedirectResponse
    {
        /** @var UserExchangeTransactions $transaction */
        $inputs      = request()->all();
        $transaction = UserExchangeTransactions::find($id);
        //  return dd($transaction->bonusCoupon);
        if (
            $transaction->payment_way === 'cash_deposit' &&
            ((int) $inputs['status'] === 2 || (isset($inputs['status']) && (int) $inputs['status'] === 3)) && isset($transaction->bonusCoupon)
        ) {
            $transaction->bonusCoupon->delete();
        }

        if (isset($inputs['notes']) && $inputs['notes'] !== '') {
            $transaction->notes = $inputs['notes'];
            unset($transaction['edtDate']);
            $transaction->save();
        }

        if (isset($inputs['status']) && (int) $inputs['status'] === 1 && (int) $inputs['status'] !== $transaction->status) {
            $transaction->status      = $inputs['status'];
            $transaction->approved_at = Carbon::now();
            $transaction->approved_by = Auth::user()->name;
        }

        if (isset($inputs['status']) && (int) $inputs['status'] === 2 && (int) $inputs['status'] !== $transaction->status) {
            $transaction->status      = $inputs['status'];
            $transaction->rejected_at = Carbon::now();
            $transaction->rejected_by = Auth::user()->name;
        }

        if (isset($inputs['status']) && (int) $inputs['status'] === 3 && (int) $inputs['status'] !== $transaction->status) {
            $transaction->status    = $inputs['status'];
            $transaction->failed_at = Carbon::now();
            $transaction->failed_by = Auth::user()->name;
        }

        if (isset($inputs['status']) && (int) $inputs['status'] === 5 && (int) $inputs['status'] !== $transaction->status) {
            $transaction->status    = $inputs['status'];
            $transaction->refund_at = Carbon::now();
            $transaction->refund_by = Auth::user()->name;
        }

        if (isset($inputs['status']) && (int) $inputs['status'] !== $transaction->status) {
            $transaction->changeStatus((int) $inputs['status']);
            //If is cash deposit, update queue.
            if ($transaction->payment_way === 'cash_deposit') {
                Banker::assignExchangeTransactions(User::find($transaction->attended_by));
            }
            //admins email
            //            $data = [
            //                'status'         => $transaction->status,
            //                'transaction_id' => $transaction->tracking_id,
            //                'url'            => URL::to('/exchange-transaction/' . $transaction->id),
            //            ];

            //            Mail::to([
            //                'gdf@americankryptosbank.com',
            //                'gsq@americankryptosbank.com'
            //            ])->send(new TransactionStatus($data));

            if ((int) $inputs['status'] !== 4) {
                //merchant email
                $data2 = [
                    'name'           => $transaction->merchant->name,
                    'status'         => $transaction->status,
                    'transaction_id' => $transaction->tracking_id,
                    'url'            => URL::to('/transaction-success/' . $transaction->id),
                ];

                Mail::to([$transaction->merchant->email])->send(new MerchantTransactionStatus($data2));
            }
        }

        return Redirect::to('/exchange-transactions-list/')->with('success', 'Customer transaction has been updated.');
    }

    public function exchangePagination()
    {
        $inputs = request();
        //return dd($inputs->all());
        $currentPage = $inputs->page;

        Paginator::currentPageResolver(function () use ($currentPage) {
            return $currentPage;
        });

        if ((int) $inputs['order_by_trader'] === 1) {
            $transactions = UserExchangeTransactions::orderBy('trader_id', 'ASC')->whereNotNull('trader_id');
        } else {
            $transactions = UserExchangeTransactions::orderBy('id', 'DESC');
        }

        $transactions->where('created_at', '<=', $inputs['end_date'])->where('created_at', '>=', $inputs['start_date']);

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

        if ($inputs['transactions_status'] !== 'All') {
            $transactions = $transactions->where(['status' => (int) $inputs['transactions_status']]);
        }

        if (isset($inputs['bank_name']) && !is_null($inputs['bank_name'])) {
            $transactions->whereHas('destinationAccount', function ($q) use ($inputs) {
                $q->where('bank_name', 'like', '%' . $inputs['bank_name'] . '%');
            });
        }

        if (isset($inputs['user_name']) && !is_null($inputs['user_name'])) {
            $transactions->whereHas('merchant', function ($q) use ($inputs) {
                $q->where('name', 'like', '%' . $inputs['user_name'] . '%');
            });
        }

        if (isset($inputs['to_send']) && !is_null($inputs['to_send']) && (float) $inputs['to_send'] > 0) {
            $transactions->where('sender_fiat_amount', (float) $inputs['to_send']);
        }

        if (isset($inputs['to_receive']) && !is_null($inputs['to_receive']) && (float) $inputs['to_receive'] > 0) {
            $transactions->where('receiver_fiat_amount', (float) $inputs['to_receive']);
        }

        //        dd($transactions->paginate(10));

        $transactions = $transactions->paginate(10);


        foreach ($transactions as $key => $transaction) {
            $transactions[$key]['userAccount'] = $transaction['userAccount']->getRating();
        }

        return $transactions;
    }

    /**
     * @param int $id
     *
     * @return mixed
     */
    public function changePayed(int $id)
    {
        $transaction = UserExchangeTransactions::find($id);
        $transaction->changePayed();

        return json_encode($transaction);
    }

    public function changeStatus($id)
    {
        $inputs = request()->all();

        $transaction = UserExchangeTransactions::find($id);
        $transaction->changeStatus((int) $inputs['params']['status']);

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

        Mail::to([$transaction->merchant->email])->send(new MerchantTransactionStatus($data2));

        return $transaction;
    }

    public function cryptocurrencyTransactions2($id, Request $request, Movimiento $model)
    {
        $request->validate([
            'transaction' => 'required',
        ]);

        $userTransaction = UserWalletsTransactions::find($id);

        if ($userTransaction->receiver_fiat !== 'USD' && $userTransaction->sender_fiat !== 'USD') {
            $usd_price = number_format($userTransaction->receiver_fiat_amount / $userTransaction->walletTransaction->exchange_rate);
        } else {
            $usd_price = $userTransaction->receiver_fiat_amount;
        }

        $type = (count($request->transaction) > 1) ? 'Partial' : 'Complet';

        if ($type === 'Complet') {
            $transaction = Transaction::find($request->transaction[0]);
            $this->updateIncomingBtcTransaction($transaction, $userTransaction, $usd_price);
        } else {
            $transaction = Transaction::whereIn('id', $request->transaction)->get();
            $sum_amount = 0;

            foreach ($transaction as $items) {
                $sum_amount += $items->amount;
            }

            foreach ($transaction as $item) {
                $num = number_format($usd_price * ($item->amount / $sum_amount * 100) / 100, 2);
                $this->updateIncomingBtcTransaction($transaction, $userTransaction, $num);
            }
        }

        unset($userTransaction['edtDate']);
        $userTransaction->bank_move = true;
        $userTransaction->update();

        return redirect()->back()->with('success', 'Movimiento de banco creado con exito')->withInput();
    }

    /**
     * @param Transaction              $transaction
     * @param UserWalletsTransactions  $userTransaction
     * @param                          $usd_price
     */
    private function updateIncomingBtcTransaction(Transaction $transaction, UserWalletsTransactions $userTransaction, $usd_price): void
    {
        $transaction->customer_name = $userTransaction->userAccount->name;
        $transaction->usd_price = $usd_price;
        $transaction->user_exchange_transaction_id = $userTransaction->id;
        $transaction->salida = 'Incoming wallet';
        $transaction->bank_move = true;
        $transaction->bank_name = 'LOCALBITCOINS';
        $transaction->error = 0;
        $transaction->update();

        IncomingBtc::where('transaction_id', $transaction->id)->update(['usd_price' => $usd_price]);
    }

    /**
     * @param            $id
     * @param Request    $request
     * @param Movimiento $model
     *
     * @return RedirectResponse
     */
    public function cryptocurrencyTransactions($id, Request $request, Movimiento $model)
    {
        $request->validate([
            'transaction' => 'required',
        ]);

        $userTransaction = UserExchangeTransactions::find($id);

        if ($userTransaction->receiver_fiat !== 'USD' && $userTransaction->sender_fiat !== 'USD') {
            $usd_price = number_format($userTransaction->receiver_fiat_amount / $userTransaction->walletTransaction->exchange_rate);
        } else {
            $usd_price = $userTransaction->sender_fiat_amount;
        }

        $type = (count($request->transaction) > 1) ? 'Partial' : 'Complet';

        if ($type === 'Complet') {
            $transaction = Transaction::find($request->transaction[0]);
            $this->updateOutgoingBtcTransaction($transaction, $userTransaction, $usd_price);
        } else {
            $transactions = Transaction::whereIn('id', $request->transaction)->get();
            $sum_amount = 0;

            foreach ($transactions as $transaction) {
                $sum_amount += $transaction->amount;
            }

            foreach ($transactions as $transaction) {
                $num = number_format($usd_price * ($transaction->amount / $sum_amount * 100) / 100, 2);
                $this->updateOutgoingBtcTransaction($transaction, $userTransaction, $num);
            }
        }

        unset($userTransaction['edtDate']);
        $userTransaction->bank_move = true;
        $userTransaction->update();

        return redirect()->back()->with('success', 'Movimiento de banco creado con exito')->withInput();
    }

    /**
     * @param Transaction              $transaction
     * @param UserExchangeTransactions $userTransaction
     * @param                          $usd_price
     */
    private function updateOutgoingBtcTransaction(Transaction $transaction, UserExchangeTransactions $userTransaction, $usd_price): void
    {
        $transaction->customer_name = $userTransaction->userAccount->name;
        $transaction->usd_price = $usd_price;
        $transaction->user_exchange_transaction_id = $userTransaction->id;
        $transaction->salida = 'Transaction';
        $transaction->bank_move = true;
        $transaction->bank_name = 'LOCALBITCOINS';
        $transaction->error = 0;
        $transaction->update();

        $outgoingBtcDB              = OutgoingBtc::where('transaction_id', $transaction->id)->first();
        $outgoingBtcDB['category']  = 1;
        $outgoingBtcDB['usd_price'] = $usd_price;
        $outgoingBtcDB['profit']    = TransactionController::simpleProfitCalculation($outgoingBtcDB, $usd_price );
        $outgoingBtcDB->save();
    }

    public function paymentBank($id, Request $request, Movimiento $model)
    {


        $request->validate([
            'banco_id' => 'required',
            'comision' => 'required',
        ]);


        $emi = explode('-', $request->emision);
        $emision = $emi[2] . '-' . $emi[0] . '-' . $emi[1];
        $request->except(['emision', 'comision']);
        $comision = str_replace(',', '', $request->comision);


        $transaction = UserExchangeTransactions::find($id);



        $monto = $transaction->receiver_fiat_amount;
        $montoycomision = $transaction->receiver_fiat_amount + $comision;
        $monto_usd =   $transaction->sender_fiat_amount;
        $tasa =  $transaction->exchange_rate;


        $banco = Banco::find($request->banco_id);


        if ($montoycomision > $banco->saldo) {

            return redirect()->back()->with('error', 'El monto es mayor al saldo disponible')->withInput();
        }

        $operacion = 'EGRESO';
        $input = [
            'user_id' => $transaction->user_id,
            'banco_id' => $request->banco_id,
            'cuenta_id' => 4,
            'tipo' => 'TRANFERENCIA NEGATIVA',
            'monto' => $monto * -1,
            'monto_bruto' => $transaction->receiver_fiat_amount,
            'moneda' => $transaction->receiver_fiat,
            'descripcion' => 'Customer Transaction',
            'doc_id' => $transaction->id,
            'operacion' => $operacion,
            'tasa' => $tasa,
            'monto_usd' =>  $monto_usd * -1,
            'capture' => null,
            'emision' =>  $emision,
            'comision' =>  $comision,
            'lote' => uniqid(),
        ];



        $result = $model->create($input);
        moverSaldo($result->banco_id);


        robotEgresos($monto,  $transaction->receiver_fiat,$operacion,$tasa,$request,$result,'Customer Transaction');

        if ($comision != '' && $comision != 0) {

            $monto_usd = $comision / $tasa;
            $result = $model->create(array_merge($input, [
                'emision' => Carbon::now(),
                'comision' => null,
                'capture' =>   null,
                'user_id' => Auth::id(), 
                'tasa' => $tasa,
                'monto_bruto' =>   $comision * -1,
                'monto' =>  $comision * -1,
                'cuenta_id' => 9,
                'operacion' => 'EGRESO',
                'monto_usd' => ($operacion === 'sum') ? $monto_usd :  $monto_usd * -1
            ]));

            robotEgresos($comision,  $transaction->receiver_fiat,$operacion,$tasa,$request,$result,'Incoming Fiat Transaction');

        }
        unset($transaction['edtDate']);
        $transaction->bank_move = true;
        $transaction->update();

        return redirect()->back()->with('success', 'Movimiento de banco creado con exito')->withInput();
    }

    public function paymentBankIncoming($id, Request $request, Movimiento $model)
    {
        $emi = explode('-', $request->emision);
        $emision = $emi[2] . '-' . $emi[0] . '-' . $emi[1];
        $request->except(['emision']);

        $transaction = UserWalletsTransactions::find($id);


        $monto =  $transaction->sender_fiat_amount;
        $monto_usd = $transaction->sender_fiat === 'USD' ?  $transaction->sender_fiat_amount : $transaction->amount;
        $tasa =  $request->exchange_rate;



        $banco = Banco::find($request->banco_id);




        $operacion = 'INGRESO';
        $input = [
            'user_id' => $transaction->user_id,
            'banco_id' => $request->banco_id,
            'cuenta_id' => 4,
            'tipo' => 'TRANFERENCIA POSITIVA',
            'monto' => $monto,
            'moneda' => $transaction->receiver_fiat,
            'descripcion' => 'Incoming Wallet Transaction',
            'operacion' => $operacion,
            'tasa' => $tasa,
            'monto_usd' =>  $monto_usd,
            'capture' => null,
            'emision' =>  $emision,
            'lote' => uniqid(),
            'doc_id' => $transaction->id,
        ];
        $result = $model->create($input);
        moverSaldo($result->banco_id);

        $lot_id = uniqid();
        $lote = new Lote;
        $lote->lote = $lot_id;
        $lote->tasa =  $tasa;
        $lote->banco_id = $request->banco_id;
        $lote->movimiento_id =  $result->id;
        $lote->monto =  $monto;
        $lote->currency = $banco->moneda;
        $lote->saldo = 0;
        $lote->save();

        $loted = new Lotedetalle;
        $loted->lote_id =  $lote->id;
        $loted->lote =   $lot_id;
        $loted->monto =    $monto;
        $loted->currency =  $banco->moneda;
        $loted->operacion =  $operacion;
        $loted->tasa = $tasa;
        $loted->banco_id = $request->banco_id;
        $loted->movimiento_id =  $result->id;
        $loted->comentarios =  'Incoming Wallet Transaction';
        $loted->save();


        $lote = Lote::all();
        foreach ($lote as $key2 => $value2) {
            moverLote($value2->id);
        }


        $transaction->bank_move = true;
        $transaction->update();

        return redirect()->back()->with('success', 'Movimiento de banco creado con exito')->withInput();
    }

    public function postPaymentData($id)
    {


        $inputs    = request()->all();
        $date      = new Carbon($inputs['deposit_date']);
        $file_name = '';

        if (isset($inputs['file'])) {
            $file_name = strtolower(str_replace(' ', '', $inputs['file']->getClientOriginalName()));
            $file_name = preg_replace('/[^A-Za-z0-9 _ .-]/', '', $file_name);

            $inputs['file']->move(base_path() . '/public/img/exchange-payment-data/' . $id . '/', $file_name);
        }

        ExchangePaymentData::create([
            'exchange_id'     => $id,
            'bank_name'       => $inputs['bank_name'],
            'deposit_number'  => $inputs['deposit_number'],
            'account_number'  => $inputs['account_number'],
            'deposit_date'    => $date,
            'attachment_name' => $file_name,
        ]);

        $transaction = UserExchangeTransactions::find($id);

        //        $data = [
        //            'transaction_id' => $transaction->tracking_id,
        //            'url'            => URL::to('/exchange-transaction/' . $transaction->id),
        //        ];

        //        Mail::to([
        //            'gdf@americankryptosbank.com',
        //            'gsq@americankryptosbank.com'
        //        ])->send(new PaymentFile($data));

        $data1 = [
            'name' => $transaction->merchant->name,
            'url'  => URL::to('/transaction-success/' . $transaction->id),
        ];

        Mail::to($transaction->merchant->email)->send(new TransactionCompleted($data1));

        return Redirect::back()->with('success', 'Los datos del deposito han sido guardados');
    }

    public function searchTransaction()
    {
        $inputs = request()->all();

        if ($inputs['trans_id'] === null) {
            if ($inputs['purpose_id'] == 1) {
                return UserWalletsTransactions::orderBy('id', 'DESC')
                    ->with('userAccount')->paginate(10);
            } else {
                return UserExchangeTransactions::orderBy('id', 'DESC')
                    ->with('userAccount')->paginate(10);
            }
        }

        $transactions = UserExchangeTransactions::where('tracking_id', $inputs['trans_id'])
            ->with('userAccount')
            ->first();


        if ($transactions === null) {
            $transactions = UserWalletsTransactions::where('tracking_id', $inputs['trans_id'])
                ->with('userAccount')
                ->first();
            if ($transactions === null) {
                return ['error' => 'Transaction not found.'];
            }
        }

        $transactions['userAccount'] = $transactions['userAccount']->getRating();

        return ['data' => [$transactions]];
    }

    /**
     * @param $id
     *
     * @return RedirectResponse
     */
    public function markAsPayed($id): RedirectResponse
    {

        //  dd($transaction->payment_way );
        $this->changePayed($id);
        //        $transaction           = UserExchangeTransactions::find($id);
        //        $transaction->is_payed = 1;
        //        $transaction->payed_at = Carbon::now()->format('Y-m-d H:i:s');
        //        $transaction->payed_by = 'Trader Master: ' . Auth::user()->name;
        //        $transaction->save();
        $transaction           = UserExchangeTransactions::find($id);
        if ($transaction->payment_way === 'zelle') {

            $model = new Movimiento;
            $comision = 0;

            $monto = $transaction->sender_fiat_amount + $comision;
            $monto_usd =   $transaction->sender_fiat_amount;
            $tasa =  $transaction->exchange_rate;

             ///zell cuenta
            $banco = Banco::find(24);


            $operacion = 'INGRESO';
            $input = [
                'user_id' => $transaction->user_id,
                'banco_id' => $banco->id,
                'cuenta_id' => 4,
                'tipo' => 'DEPOSITO',
                'monto' => $monto,
                'monto_bruto' => $monto,
                'moneda' => $transaction->sender_fiat,
                'descripcion' => 'Zeller tracking id:' . $transaction->tracking_id,
                'operacion' => $operacion,
                'tasa' => 1,
                'monto_usd' =>  $monto_usd,
                'capture' => null,
                'emision' =>  Carbon::now(),
                'comision' =>  $comision,
                'lote' => uniqid(),
            ];
            $result = $model->create($input);



            moverSaldo($result->banco_id);
            $loteid =  uniqid();

            $lote = new Lote;
            $lote->lote = $loteid;
            $lote->tasa =  $tasa;
            $lote->banco_id = $banco->id;
            $lote->movimiento_id =  $result->id;
            $lote->monto =  $monto;
            $lote->currency = $banco->moneda;
            $lote->saldo = 0;
            $lote->save();

            $loted = new Lotedetalle;
            $loted->lote_id =  $lote->id;
            $loted->lote =   $loteid;
            $loted->monto =    $monto;
            $loted->currency =  $banco->moneda;
            $loted->operacion =  $operacion;
            $loted->tasa = $tasa;
            $loted->banco_id = $banco->id;
            $loted->movimiento_id =  $result->id;
            $loted->comentarios =  'Zeller tracking id:' . $transaction->tracking_id;
            $loted->save();

            $lote = Lote::all();
            foreach ($lote as $key2 => $value2) {
                moverLote($value2->id);
            }
            unset($transaction['edtDate']);
            //  $transaction->bank_move = true;
            $transaction->update();
        }


        return Redirect::to('/exchange-transaction/' . $id);
    }

    public function getQueue($id)
    {
        $ordersInQueue = UserExchangeTransactions::where(
            [
                'attended_by' => null,
                'payment_way' => 'cash_deposit',
                'status'      => 0
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

    /**
     * @param $order_id
     *
     * @return array
     */
    public function reclaimOrder($order_id)
    {
        $order = UserExchangeTransactions::find($order_id);
        unset($order['edtDate']);
        $order->attended_by = Auth::user()->id;
        $order->attended_at = Carbon::now()->format('Y-m-d H:i:s');
        $order->save();


        Pusher::trigger(
            'my-channel',
            'notification-chat-' . $order_id,
            [
                'attended'    => 1,
                'sender_id'   => Auth::user()->id,
                'attended_by' => Auth::user()->name
            ]
        );

        return Auth::user()->name;
    }

    /**
     * @param $id
     *
     * @return array
     */
    public function assignExchange($id)
    {
        $transaction = UserExchangeTransactions::find($id);
        unset($transaction['edtDate']);

        if (Auth::user()->assignedExchanges->whereNotIn('status', [1, 2, 3, 4, 5])->count() >= 5) {
            return [
                'status' => 'error',
                'msg'    => 'Ya has alcanzado el limite de transacciones asignadas.'
            ];
        }

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
                'msg'    => 'Se te ha asignado una nueva transaccion.'
            ];
        }

        return [
            'status' => 'error',
            'msg'    => 'Esta transaccion ya ha sido asignada a alguien mas.'
        ];
    }

    public function resolviendoAssignacion()
    {
        $trans_data = [
            [589, 4],
            [597, 4],
            [623, 1385],
            [624, 4],
            [630, 6],
            [631, 1385],
            [632, 6],
            [636, 4],
            [638, 1385],
            [639, 878],
            [640, 4],
            [641, 6],
            [642, 6],
            [643, 893],
            [644, 4],
            [645, 4],
            [646, 4],
            [647, 6],
            [648, 6],
            [649, 4],
            [650, 1385],
            [652, 1385],
            [653, 4],
            [654, 4],
            [655, 1385],
            [656, 1385],
            [657, 1385],
            [658, 878],
            [659, 1385],
            [660, 1385],
            [661, 6],
            [662, 6],
            [663, 1385],
            [664, 878],
            [665, 878],
            [666, 878],
            [667, 1385],
            [668, 1385],
            [669, 4],
            [670, 4]
        ];

        foreach ($trans_data as $trans) {

            $user = User::find($trans[1]);

            $transaction = UserExchangeTransactions::find($trans[0]);

            unset($transaction['edtDate']);

            $transaction->update([
                'trader_id'   => $user->id,
                'trader_info' => [
                    'name'  => $user->name,
                    'id'    => $user->id,
                    'email' => $user->email,
                ]
            ]);
        }

        return 'success';
    }

    public function getTransactionData($order_id)
    {
        return response()->json(UserExchangeTransactions::find($order_id));
    }

    public function getCsv()
    {
        $inputs = request();
        //return dd($inputs->all());
        $currentPage = $inputs->page;

        Paginator::currentPageResolver(function () use ($currentPage) {
            return $currentPage;
        });

        $transactions = UserExchangeTransactions::orderBy('id', 'DESC');

        $transactions->where('created_at', '<=', $inputs['end_date'])->where(
            'created_at',
            '>=',
            $inputs['start_date']
        )->with('trader');

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

        if ($inputs['transactions_status'] !== 'All') {
            $transactions = $transactions->where(['status' => (int) $inputs['transactions_status']]);
        }

        if (isset($inputs['bank_name']) && !is_null($inputs['bank_name'])) {
            $transactions->whereHas('destinationAccount', function ($q) use ($inputs) {
                $q->where('bank_name', 'like', '%' . $inputs['bank_name'] . '%');
            });
        }

        if (isset($inputs['user_name']) && !is_null($inputs['user_name'])) {
            $transactions->whereHas('merchant', function ($q) use ($inputs) {
                $q->where('name', 'like', '%' . $inputs['user_name'] . '%');
            });
        }

        if (isset($inputs['to_send']) && !is_null($inputs['to_send']) && (float) $inputs['to_send'] > 0) {
            $transactions->where('sender_fiat_amount', (float) $inputs['to_send']);
        }

        if (isset($inputs['to_receive']) && !is_null($inputs['to_receive']) && (float) $inputs['to_receive'] > 0) {
            $transactions->where('receiver_fiat_amount', (float) $inputs['to_receive']);
        }

        $transCsvs = $transactions->get();

        $csvExporter = new \Laracsv\Export();

        $csvExporter->beforeEach(function ($transCsv) {
            $transCsv->sender_fiat_amount   = number_format($transCsv->sender_fiat_amount, 2);
            $transCsv->receiver_fiat_amount = number_format($transCsv->receiver_fiat_amount, 2);
            $transCsv->payment_method       = $transCsv->paymentMethod();
            $transCsv->status_name          = $transCsv->getStatus();
        });

        $csvExporter->build(
            $transCsvs,
            [
                'trader.name'                  => 'Trader',
                'merchant.name'                => 'Remitente',
                'sender_fiat'                  => 'Moneda a enviar',
                'sender_fiat_amount'           => 'Monto a enviar',
                'receiver_fiat'                => 'Moneda a Recibir',
                'receiver_fiat_amount'         => 'Monto a recibir',
                'destinationAccount.bank_name' => 'Banco',
                'payment_method'               => 'Metodo de pago',
                'status_name'                  => 'Status',
                'created_at'                   => 'Fecha'
            ]
        )->download();
    }
}
