<?php

namespace App\Http\Controllers;

use App\ActiveContactCache;
use App\Akb\Banker;
use App\ApiHelper;
use App\Banco;
use App\canceledContactCache;
use App\LocalBitcoins_Trades_API;
use App\OutgoingBtcCache;
use App\VesUsdPrices;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Mail;
use App\Mail\TransactionsErrorMessage;
use App\Mail\RemainingOrangeAlert;
use App\Mail\RemainingRedAlert;
use Pusher\Laravel\Facades\Pusher;

use App\Transaction;
use App\CurrencyWallet;
use App\LocalWallet;
use App\LocalTrades;
use GuzzleHttp\Client;
use App\Credential;
use App\BtcBssData;
use App\UsdBssData;
use Carbon\Carbon;
use App\BitstampData;
use App\IncomingBtc;
use App\OutgoingBtc;
use App\ErrorTransaction;
use App\Lote;
use App\Lotedetalle;
use App\Movimiento;
use Auth;
use DB;

class TransactionController extends Controller
{
    public function __construct(Credential $credential)
    {
        $this->credential = Credential::where('is_active', 1)->first();
    }

    public function getCreate()
    {
        //wallet balance
        $balance = LocalWallet::walletBalance();

        $ves = CurrencyWallet::where('currency', 'VES')->first();
        $usd = CurrencyWallet::where('currency', 'USD')->first();

        return view('transactions.create-v2')->with(compact('balance', 'ves', 'usd'));
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postCreate(): \Illuminate\Http\RedirectResponse
    {
        $inputs       = request()->all();
        $releasedDate = new Carbon($inputs['transaction_date']);
        $transaction  = Transaction::create([
            'bank_name'      => $inputs['origin_name'],
            'transaction_id' => $inputs['new_transaction_id'],
            'amount'         => $inputs['amount'],
            'released_date'  => $releasedDate,
            'currency'       => $inputs['currency'],
            'type'           => $inputs['type'],
            'account_name'   => $inputs['username'] //TODO Selectbox con los nombres de las cuentas.
        ]);

        if ($inputs['type'] === 'Outgoing') {
            IncomingBtc::create([
                'transaction_id' => $transaction->id,
                'amount_btc'     => $inputs['amount_btc'],
                'usd_price'      => $inputs['amount'],
                'released_date'  => $releasedDate->format('Y-m-d H:i:s'),
                'remaining'      => $inputs['amount_btc']
            ]);
        } else {
            $last_transaction = Transaction::orderBy('id', 'desc')->first();

            if (isset($last_transaction)) {
                $last_date = new Carbon($last_transaction->released_date);
            } else {
                $last_date = Carbon::now();
                $last_date = $last_date->subDays(30);
            }

            $operationDate = new Carbon($releasedDate);

            if ($operationDate > $last_date) {
                $incomingModel = new IncomingBtc;
                $inc_data      = $incomingModel::totalRemaining();

                if ($inputs['amount_btc'] > $inc_data['total_remaining']) {
                    /**
                     * Retorna al Form con el error en el enunciado
                     *
                     * Error de falta de ingresos para poder registrar la salida
                     **/
                    return Redirect::back()->with(
                        'error',
                        'The is not enough Incoming BTC operations to pay this Outgoing of BTC'
                    );
                }

                if ($this->incomingsReview($inputs['amount_btc']) === 1) {
                    /**
                     * Retorna al Form con el error en el enunciado
                     *
                     * Error de ingreso de Bitcoins sin costo asociado en USD
                     **/
                    return Redirect::back()->with(
                        'error',
                        'There\'s some Incoming BTC operations without USD cost'
                    );
                }
            } else {
                /**
                 * Retorna al Form con el error en el enunciado
                 *
                 * Error de por fecha en el pasado remoto
                 * !important
                 **/
                return Redirect::back()->with(
                    'error',
                    'You can not register a BTC Outgoing in the past before he last Incoming of BTC date'
                );
            }

            //Registro normal
            OutgoingBtc::create([
                'transaction_id' => $transaction->id,
                'amount_btc'     => $inputs['amount_btc'],
                'fee_btc'        => $inputs['fee_btc'], //TODO
                'total_btc'      => $inputs['total_btc'], //TODO
                'usd_price'      => $inputs['amount'],
                'released_date'  => $releasedDate->format('Y-m-d H:i:s')
            ]);
        }

        //        $wallet = CurrencyWallet::where('currency', $data['currency'])->first();
        //
        //        if ($data['type'] == 'Incoming') {
        //            $wallet->incomingTransaction($data['amount']);
        //        } elseif ($data['type'] == 'Outgoing') {
        //            $wallet->outgoingTransaction($data['amount']);
        //        }

        return Redirect::back()->with('success', 'Transaction has been created.');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getList()
    {
        $transactions = Transaction::with(['incomingBtc', 'outgoingBtc'])
            ->orderBy('released_date', 'desc')
            ->paginate(16);

        $outgoingBtcs = OutgoingBtc::orderBy('released_date', 'desc')
            //->where('account_name', '=', 'cristinadlr')
            ->select(['released_date', 'profit', 'usd_price', 'category'])
            ->get();

        $incomingBtcs = IncomingBtc::orderBy('released_date', 'desc')
            //->where('account_name', '=', 'cristinadlr')
            ->get();

        $profit             = [];
        $loss               = [];
        $income             = [];
        $expenses           = [];
        $simpleMonthsAr     = [];
        $monthsAr           = [
            1  => 0,
            2  => 0,
            3  => 0,
            4  => 0,
            5  => 0,
            6  => 0,
            7  => 0,
            8  => 0,
            9  => 0,
            10 => 0,
            11 => 0,
            12 => 0
        ];
        $outgoingCategories = [];
        foreach ($outgoingBtcs[0]->categories as $key => $category) {
            $outgoingCategories[$key] = 0;
        }
        $simpleNoProfit = [];
        $remainder      = [];

        foreach ($outgoingBtcs as $outgoingBtc) {
            if ($outgoingBtc['profit'] < 0) {
                if ($outgoingBtc['category'] === 1) {
                    $loss[] = $outgoingBtc['profit'] * -1;
                } else {
                    $outgoingCategories[$outgoingBtc['category']] += round($outgoingBtc['profit'] * -1, 2);
                }
            } else {
                $profit[] = $outgoingBtc['profit'];
            }

            $intMonth = new Carbon($outgoingBtc['released_date']);
            $intMonth = $intMonth->month;

            ++$monthsAr[$intMonth];

            $income[] = $outgoingBtc['usd_price'];
        }

        foreach ($monthsAr as $monthAr) {
            $simpleMonthsAr[] = $monthAr;
        }

        unset($outgoingCategories[1]);
        foreach ($outgoingCategories as $expense) {
            $simpleNoProfit[] = $expense;
        }

        foreach ($incomingBtcs as $incomingBtc) {
            $expenses[] = $incomingBtc['usd_price'];

            if ($incomingBtc['remaining'] > 0) {
                $remainder[] = $incomingBtc;
            }
        }

        $profit   = round(array_sum($profit), 2);
        $loss     = round(array_sum($loss), 2);
        $income   = round(array_sum($income), 2);
        $expenses = round(array_sum($expenses), 2);

        //        $dolarPrices = VesUsdPrices::get()->toArray();
        //        $sellPrices  = [];
        //        $buyPrices   = [];
        //
        //        foreach ($dolarPrices as $price) {
        //            $venTime      = Carbon::createFromTimeString($price['created_at'], 'UTC');
        //            $venTime      = $venTime->setTimezone('EDT')->toDateTimeString();
        //            $sellPrices[] = (object)['t' => $venTime, 'y' => $price['sell_price']];
        //            $buyPrices[]  = (object)['t' => $venTime, 'y' => $price['buy_price']];
        //        }

        $viewVariables = [
            'transactions'  => compact('transactions'),
            'profitLossPie' => [$profit, $loss],
            'expensesBars'  => [$income, $expenses],
            'salesSum'      => $simpleMonthsAr,
            'noProfit'      => $simpleNoProfit,
            'remainder'     => array_reverse($remainder),
            //            'sellPrices'    => $sellPrices,
            //            'buyPrices'     => $buyPrices
        ];

        //wallet balance
        return view('coinbank.transactions.list')
            ->with($viewVariables);
    }


    /**
     * Return profit and loss by range of dates
     *
     * @return array
     */
    public function getRangeProfitLoss(): array
    {
        $inputs = request();

        $outgoingBtcs = OutgoingBtc::orderBy('released_date', 'desc')
            ->where('released_date', '>=', $inputs->start)
            ->where('released_date', '<=', $inputs->end)
            ->select(['released_date', 'profit', 'category'])
            ->get();

        $profit = [];
        $loss   = [];

        foreach ($outgoingBtcs as $outgoingBtc) {
            if ($outgoingBtc['profit'] < 0) {
                if ($outgoingBtc['category'] === 1) {
                    $loss[] = $outgoingBtc['profit'] * -1;
                }
            } else {
                $profit[] = $outgoingBtc['profit'];
            }
        }

        $profit = array_sum($profit);
        $loss   = array_sum($loss);

        return [$profit, $loss];
    }

    /**
     * Return profit and loss by range of dates
     *
     * @return array
     */
    public function getRangeNoProfit(): array
    {
        $inputs = request();

        $outgoingBtcs = OutgoingBtc::orderBy('released_date', 'desc')
            ->where('released_date', '>=', $inputs->start)
            ->where('released_date', '<=', $inputs->end)
            ->where('profit', '<', 0)
            ->where('category', '!=', 1)
            ->select(['released_date', 'profit', 'category'])
            ->get();

        $outgoingCategories = [];
        $simpleNoProfit     = [];
        foreach ($outgoingBtcs[0]->categories as $key => $category) {
            $outgoingCategories[$key] = 0;
        }

        foreach ($outgoingBtcs as $outgoingBtc) {
            $outgoingCategories[$outgoingBtc['category']] += round($outgoingBtc['profit'] * -1, 2);
        }

        unset($outgoingCategories[1]);
        foreach ($outgoingCategories as $expense) {
            $simpleNoProfit[] = $expense;
        }

        return $simpleNoProfit;
    }

    /**
     * Return sales volume
     *
     * @return array
     */
    public function getRangeSalesSum(): array
    {
        $inputs         = request();
        $simpleMonthsAr = [];
        $monthsAr       = [
            1  => 0,
            2  => 0,
            3  => 0,
            4  => 0,
            5  => 0,
            6  => 0,
            7  => 0,
            8  => 0,
            9  => 0,
            10 => 0,
            11 => 0,
            12 => 0
        ];

        $outgoingBtcs = OutgoingBtc::orderBy('released_date', 'desc')
            ->where('released_date', '>=', $inputs->start)
            ->where('released_date', '<=', $inputs->end)
            ->select(['released_date'])
            ->get();

        foreach ($outgoingBtcs as $outgoingBtc) {
            $intMonth = new Carbon($outgoingBtc['released_date']);
            $intMonth = $intMonth->month;

            ++$monthsAr[$intMonth];
        }

        foreach ($monthsAr as $monthAr) {
            $simpleMonthsAr[] = $monthAr;
        }

        //wallet balance
        return $simpleMonthsAr;
    }

    public function getRangeExpenses(): array
    {
        $inputs = request();

        $outgoingBtcs = OutgoingBtc::orderBy('released_date', 'desc')
            ->where('released_date', '>=', $inputs->start)
            ->where('released_date', '<=', $inputs->end)
            //            ->where('account_name', '=', 'cristinadlr')
            ->select(['released_date', 'profit', 'usd_price', 'category'])
            ->get();

        $incomingBtcs = IncomingBtc::orderBy('released_date', 'desc')
            ->where('released_date', '>=', $inputs->start)
            ->where('released_date', '<=', $inputs->end)
            //            ->where('account_name', '=', 'cristinadlr')
            ->select(['released_date', 'usd_price'])
            //            ->with('transactions')
            ->get();

        $income   = [];
        $expenses = [];

        foreach ($outgoingBtcs as $outgoingBtc) {
            if ($outgoingBtc['category'] === 1) {
                $income[] = $outgoingBtc['usd_price'];
            }
        }

        foreach ($incomingBtcs as $incomingBtc) {
            $expenses[] = $incomingBtc['usd_price'];
        }

        $income   = round(array_sum($income), 2);
        $expenses = round(array_sum($expenses), 2);

        //wallet balance
        return [$income, $expenses];
    }

    /**
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getFilterTransactions(): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $inputs = request();

        $transactions = Transaction::with(['incomingBtc', 'outgoingBtc'])
            ->where('released_date', '>=', $inputs->start)
            ->where('released_date', '<=', $inputs->end);

        if ($inputs->currency === 'all' && $inputs->type !== 'all') {
            $transactions->where('type', $inputs->type);
        }

        if ($inputs->currency !== 'all' && $inputs->type === 'all') {
            $transactions->where('currency', $inputs->currency);
        }

        if ($inputs->currency !== 'all' && $inputs->type !== 'all') {
            $transactions->where('currency', $inputs->currency)
                ->where('type', $inputs->type);
        }

        if ($inputs->filterProfit !== '-1') {
            if ($inputs->filterProfit === '1') {
                //die;
                $transactions->whereHas('outgoingBtc', function ($query) {
                    $query->where('profit', 'regexp', '[-]');
                });
            } else {
                $transactions->whereHas('outgoingBtc', function ($query) {
                    $query->where('profit', 'not regexp', '[-]');
                });
            }
        }

        //dd([$transactions, $inputs->filterProfit]); die;

        return $transactions->orderBy('released_date', 'desc')->paginate(16);
    }

    public function calculateBtcSale()
    {
        $inputs         = request();
        $incomingBTC    = IncomingBtc::orderBy('released_date', 'asc')
            ->where('account_name', '=', $inputs->account)
            ->where('remaining', '>', 0)
            ->get();
        $highestRate    = [];
        $fragmentsToUSe = ApiHelper::calculateSellingBtc($incomingBTC, $inputs->btcAmountToSell);

        if (!is_array($fragmentsToUSe)) {
            return ['error' => 'No BTC'];
        }

        foreach ($fragmentsToUSe as $fragment) {
            if ($fragment['usd_price'] === null) {
                return ['error' => 'No Price'];
            }

            $highestRate[] = $fragment['usd_price'] / $fragment['amount_btc'];
        }

        $highestRate       = round(max($highestRate), 2);
        $highestRateProfit = $highestRate * $inputs->estimatedProfit / 100;
        $highestRateProfit = round($highestRate + $highestRateProfit, 2);

        return ['fragmentsToUse' => $fragmentsToUSe, 'suggestedRate' => $highestRateProfit];
    }

    public function getFilterDaterange()
    {
        $inputs = request();

        if ($inputs->start && $inputs->end) {
            return Transaction::orderBy('released_date', 'desc')
                ->where('released_date', '>=', $inputs->start)
                ->where('released_date', '<=', $inputs->end)
                ->with(['incomingBtc', 'outgoingBtc'])
                ->paginate(16);
        }

        return null;
    }

    /**
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getTransactionsPagination(): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $inputs      = request();
        $currentPage = $inputs->page;

        Paginator::currentPageResolver(function () use ($currentPage) {
            return $currentPage;
        });

        $transactions = Transaction::with(['incomingBtc', 'outgoingBtc'])
            ->where('released_date', '>=', $inputs->start)
            ->where('released_date', '<=', $inputs->end);

        if ($inputs->currency === 'all' && $inputs->type !== 'all') {
            $transactions->where('type', $inputs->type);
        }

        if ($inputs->currency !== 'all' && $inputs->type === 'all') {
            $transactions->where('currency', $inputs->currency);
        }

        if ($inputs->currency !== 'all' && $inputs->type !== 'all') {
            $transactions->where('currency', $inputs->currency)
                ->where('type', $inputs->type);
        }

        if ($inputs->filterProfit !== '-1') {
            if ($inputs->filterProfit === '1') {
                //die;
                $transactions->whereHas('outgoingBtc', function ($query) {
                    $query->where('profit', 'regexp', '[-]');
                });
            } else {
                $transactions->whereHas('outgoingBtc', function ($query) {
                    $query->where('profit', 'not regexp', '[-]');
                });
            }
        }

        return $transactions->orderBy('released_date', 'desc')->paginate(16);
    }

    public function getEdit($id)
    {



        $transaction = Transaction::find($id);

        if ($transaction->type === 'Incoming') {
            $btc_trans = OutgoingBtc::where('transaction_id', $transaction->id)
                ->with(['transaction'])
                ->first();
        } else {
            $btc_trans = IncomingBtc::where('transaction_id', $transaction->id)->first();
        }

        $fundedDate = isset($transaction['json_data']['funded_at']) ? new Carbon($transaction['json_data']['funded_at']) : new Carbon($transaction['localbtc_released_date']);
        $fundedDate = $fundedDate->format('Y-m-d H:i:s');
        $bitstamp   = BitstampData::where(
            'created_at',
            '>=',
            $fundedDate
        )->first();

        //        dd($transaction, $btc_trans);

        return view('transactions.edit')->with(compact('transaction', 'btc_trans', 'bitstamp'));
    }

    /**
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */



    public function postMoveBankInc($id, $inputs)
    {

        
        $amount = str_replace(',', '', $inputs->amount);
        $usd_price = str_replace(',', '', $inputs->usd_price);
        $comision = str_replace(',', '', $inputs->fee_bank);



        $model = new Movimiento;


        //ENTRADA DE DINERO
        $transaction = Transaction::find($id);
        $banco = Banco::find($inputs->banco_id);
        $monto =  $amount;
        $monto_usd = $usd_price;

        $tasa = ($amount / $usd_price);

        if ($monto > $banco->saldo) {

            return redirect()->back()->with('error', 'El monto es mayor al saldo disponible')->withInput();
        }

        $operacion = 'EGRESO';
        $input = [
            'user_id' => Auth::id(),
            'banco_id' => $banco->id,
            'cuenta_id' => 4,
            'tipo' => 'TRANFERENCIA NEGATIVA',
            'monto' => $monto * -1,
            'moneda' =>  $banco->moneda,
            'descripcion' => 'Incoming Fiat Transaction',
            'operacion' => $operacion,
            'tasa' => $tasa,
            'doc_id' => $transaction->id,
            'monto_usd' =>  $monto_usd * -1,
            'capture' => null,
            'capture' => null,
            'emision' =>  Carbon::now(),
            'lote' => uniqid(),
        ];
        $result = $model->create($input);
        moverSaldo($result->banco_id);


        
        robotEgresos($monto, $banco->moneda, 'EGRESO', $tasa,$inputs, $result, 'Incoming Fiat Transaction');

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

            robotEgresos($comision,$banco->moneda, 'EGRESO', $tasa, $inputs, $result, 'Incoming Fiat Transaction');
        }

      

        $transaction->bank_move = true;
        $transaction->fee_bank = $comision;
        $transaction->update();
        // return redirect()->back()->with('success', 'Se realizo el movimiento bancario')->withInput();


    }



    public function postMoveBankOut($id, $inputs)
    {

        //  dd('postMoveBankOut');
        $amount = str_replace(',', '', $inputs->amount);
        $usd_price = str_replace(',', '', $inputs->usd_price);

        $model = new Movimiento;


        //ENTRADA DE DINERO
        $transaction = Transaction::find($id);
        $banco = Banco::find($inputs->banco_id);

        //  dd(  $inputs, $transaction, $banco);

        /*   $generalCountries = Banker::$generalCountries;
        $cc = ''; */


        /*   foreach ($generalCountries as $key => $region) {
            foreach ($region as $rKey => $country) {
                // $country[3] == $banco->moneda;
                if ($country[3] == $banco->moneda) {
                    $cc = $country[0];
                }
            }
        }
    
         
 
       // dd( $cc);
        if ($cc == '') {
            return redirect()->back()->with('error', 'No se pudo calcular la tasa')->withInput();
        } */



        $monto =  $amount;
        $monto_usd = $usd_price;
        //$tasa2 = calcularTasa($monto_usd, $monto, $banco->moneda, $cc) ?? 1;
        //$tasa =  $tasa2[0];


        $tasa = ($amount / $usd_price);



        $operacion = 'INGRESO';
        $input = [
            'user_id' => Auth::id(),
            'banco_id' => $banco->id,
            'cuenta_id' => 4,
            'tipo' => 'TRANFERENCIA POSITIVA',
            'monto' => $monto,
            'moneda' =>  $banco->moneda,
            'descripcion' => 'Outgoing LocalBitcoint Transaction',
            'operacion' => $operacion,
            'tasa' => $tasa,
            'monto_usd' =>  $monto_usd,
            'capture' => null,
            'emision' =>  Carbon::now(),
            'lote' => uniqid(),
        ];
        $result = $model->create($input);
        moverSaldo($result->banco_id);

        $lot_id = uniqid();
        $lote = new Lote();
        $lote->lote = $lot_id;
        $lote->tasa =  $tasa;
        $lote->banco_id = $banco->id;
        $lote->movimiento_id =  $result->id;
        $lote->monto =  $monto;
        $lote->currency = $banco->moneda;
        $lote->saldo = 0;
        $lote->save();

        $loted = new Lotedetalle();
        $loted->lote_id =  $lote->id;
        $loted->lote =   $lot_id;
        $loted->monto =    $monto;
        $loted->currency =  $banco->moneda;
        $loted->operacion =  $operacion;
        $loted->tasa = $tasa;
        $loted->banco_id = $banco->id;
        $loted->movimiento_id =  $result->id;
        $loted->comentarios =  'Outgoing LocalBitcoint Transaction';
        $loted->save();


        $lote = Lote::all();
        foreach ($lote as $key2 => $value2) {
            moverLote($value2->id);
        }


        $transaction->bank_move = true;
        $transaction->update();

        //  return redirect()->back()->with('success', 'Se realizo el movimiento bancario')->withInput();


    }
    public function postEdit($id): \Illuminate\Http\RedirectResponse
    {
        $inputs = request();
        $input2 =  $inputs->except(['amount', 'usd_price', 'fee_bank']);
        $amount = str_replace(',', '', $inputs->amount);
        $usd_price = str_replace(',', '', $inputs->usd_price);
        $comision2 = str_replace(',', '', $inputs->fee_bank);
        $comision = ($comision2 != '') ? $comision2 : 0 ;
        $transaction = Transaction::find($id);

        if ($transaction->type === 'Incoming') {
            if ($inputs->user_id != null) {
                $transaction->bank_move = true;
                $transaction->user_id = $inputs->user_id;
                $transaction->update();

                //Update 
                $update = array_merge($input2, ['amount' => $amount, 'usd_price' => $usd_price, 'fee_bank' => $comision]);
                $transaction->update($update);
                return Redirect::to('/transactions/#list')->with('success', 'Transaction has been updated.');
            }
        }

        if ($inputs->salida === 'Bank' && $inputs->banco_id === null) {
            return redirect()->back()->with('error', 'Seleccciona el banco')->withInput();
        }

        if ($inputs->salida === 'Transaction' && $inputs->user_exchange_transaction_id === null) {
            return redirect()->back()->with('error', 'Seleccciona el Customer transaction')->withInput();
        }

        if ($inputs->salida === 'Fiat'  && $inputs->banco_id === null) {
            return redirect()->back()->with('error', 'Seleccciona el banco')->withInput();
        }

        if ($inputs->salida === 'Fiat') {
            $banco = Banco::find($inputs->banco_id);
            $monto = $amount;
            if ($monto > $banco->saldo) {

                return redirect()->back()->with('error', 'El monto es mayor al saldo disponible')->withInput();
            }
        }

        if ($inputs->salida === 'Other Exchange'  && $inputs->exchange === null) {
            return redirect()->back()->with('error', 'Seleccciona el exchange')->withInput();
        }

        //Update 
        $update = array_merge($input2, ['amount' => $amount, 'usd_price' => $usd_price, 'fee_bank' => $comision]);
        $transaction->update($update);

        if ($transaction->type === 'Outgoing') {
            IncomingBtc::where('transaction_id', $id)->update(['usd_price' => $usd_price]);
        }

        if ($transaction->type === 'Incoming') {
            //Calculate profit Logic
            $outgoingBtcDB              = OutgoingBtc::where('transaction_id', $id)->with('transaction')->first();
            $outgoingBtcDB['category']  = $inputs->category;
            $outgoingBtcDB['usd_price'] = $usd_price;
            $outgoingBtcDB['profit']    = self::simpleProfitCalculation($outgoingBtcDB, $usd_price );
            $outgoingBtcDB->save();
        }

        $error_rep = ErrorTransaction::where('was_solved', 0)
            ->where('not_usd_price', 1)
            ->orderBy('id', 'desc')
            ->first();

        if (isset($error_rep) && $transaction->type === 'Outgoing') {
            $remaining = IncomingBtc::totalRemaining(
                $error_rep->account_name,
                $error_rep->localbtc_released_date,
                null
            );
            if ($remaining['total_remaining'] >= $error_rep['amount_btc']) {
                $error_rep->was_solved = 1;
                $error_rep->save();
            }
        }

        if ($transaction->type === 'Incoming'  && $inputs->salida === 'Bank') {
            $this->postMoveBankOut($id,  $inputs);
        }

        if ($transaction->type === 'Outgoing'  && $inputs->salida === 'Fiat') {
            $this->postMoveBankInc($id,  $inputs);
        }

        return Redirect::to('/transactions/#list')->with('success', 'Transaction has been updated.');
    }

    /**
     * Simple profit calculation with known portions
     *
     * @param OutgoingBtc $outgoingBtc
     * @param float       $salePrice
     *
     * @return float
     */
    public static function simpleProfitCalculation(OutgoingBtc $outgoingBtc, float $salePrice): float
    {
        $amountBTC = $outgoingBtc->amount_btc;
        $saleRate  = $salePrice / $amountBTC;
        $saleRate  = round($saleRate, 2);
        $counter   = 0;
        $profit    = [];

        foreach ($outgoingBtc->incoming_btcs_used as $used) {
            if ($counter === 0) {
                $feeCost = $outgoingBtc->fee_btc * $used['rate'];
            } else {
                $feeCost = 0;
            }

            $profit[] = ($saleRate - $used['rate']) * $used['used'] - $feeCost;

            $counter++;
        }

        return round(array_sum($profit), 2);
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function ignRecordTransactions()
    {
        $completeList = ApiHelper::getCompleteList();
        //        $this->recordIncomingBTC($completeList);
        //        $this->recordOutgoingBtcCache($completeList);
        $this->recordOutgoingBTC($completeList);

        dd($completeList);
    }

    /**
     * Only record all outgoing BTC transactions.
     *
     * @param array $completeList
     *
     * @return bool|string
     */
    private function recordOutgoingBtcCache(array $completeList)
    {
        foreach ($completeList as $accountName => $account) {
            $lastOutgoingBtcTransaction = OutgoingBtcCache::where('account_name', $accountName)
                ->orderBy('localbtc_released_date', 'desc')
                ->first();

            $lastOutgoingBtcdate = strtotime('2019-03-01T05:00:00+00:00');

            if ($lastOutgoingBtcTransaction !== null) {
                $lastOutgoingBtcdate = strtotime($lastOutgoingBtcTransaction->localbtc_released_date);
            }

            foreach ($account['outgoingBTC'] as $outgoingBtcTransaction) {
                $localbtcReleasedDate = null;
                $contactID            = null;
                $md5                  = md5(json_encode($outgoingBtcTransaction['walletTransaction']));
                $tryExist             = OutgoingBtcCache::where('md5', $md5)->first();

                if ($tryExist === null) {
                    if (
                        $outgoingBtcTransaction['contactInfo'] !== null &&
                        $lastOutgoingBtcdate < strtotime($outgoingBtcTransaction['contactInfo']['released_at'])
                    ) {
                        //Start
                        $contactID               = $outgoingBtcTransaction['contactInfo']['contact_id'];
                        $transactionReleasedDate = new Carbon($outgoingBtcTransaction['contactInfo']['released_at']);
                        $localbtcReleasedDate    = $transactionReleasedDate->format('Y-m-d H:i:s');
                    }

                    if (
                        $outgoingBtcTransaction['contactInfo'] === null &&
                        $lastOutgoingBtcdate < strtotime($outgoingBtcTransaction['walletTransaction']['created_at'])
                    ) {
                        //Start
                        $regEx = '/#(\d+)/';
                        preg_match(
                            $regEx,
                            $outgoingBtcTransaction['walletTransaction']['description'],
                            $matches
                        );
                        $contactID               = $matches[1] ?? null;
                        $contactID               = $contactID !== null ? (int) $contactID : null;
                        $transactionReleasedDate = new Carbon($outgoingBtcTransaction['walletTransaction']['created_at']);
                        $localbtcReleasedDate    = $transactionReleasedDate->format('Y-m-d H:i:s');
                    }

                    if ($localbtcReleasedDate !== null) {
                        OutgoingBtcCache::create([
                            'contact_id'             => $contactID,
                            'account_name'           => $accountName,
                            'contactInfo'            => $outgoingBtcTransaction['contactInfo'],
                            'walletTransaction'      => $outgoingBtcTransaction['walletTransaction'],
                            'localbtc_released_date' => $localbtcReleasedDate,
                            'md5'                    => $md5,
                        ]);
                    }
                }
            }

            $outgoingBtcCacheTransactions = OutgoingBtcCache::where('account_name', $accountName)
                ->orderBy('localbtc_released_date', 'asc')
                ->get();

            //Update no longer in hold
            foreach ($outgoingBtcCacheTransactions as $oKey => $outgoingBtcCacheTransaction) {
                if (isset($outgoingBtcCacheTransaction['walletTransaction']['hold'])) {
                    //Start
                    /**
                     * @var canceledContactCache $anchorCanceledTransaction
                     * Relation in canceled contacts.
                     */
                    $anchorCanceledTransaction = canceledContactCache::where(
                        [
                            'contact_id'   => $outgoingBtcCacheTransaction['contact_id'],
                            'account_name' => $outgoingBtcCacheTransaction['account_name']
                        ]
                    )->first();

                    $anchorSelfTransaction = null;

                    if ($anchorCanceledTransaction !== null) {
                        /**
                         * If exist a relation with a canceled transaction, the incoming is not longer reserved
                         * to this transaction ID
                         */
                        $incomingsToReserve = IncomingBtc::where('hold_by', '!=', null)
                            ->get();
                        foreach ($incomingsToReserve as $incomingBTC) {
                            if (in_array($outgoingBtcCacheTransaction['id'], $incomingBTC['hold_by'])) {
                                $holdBy    = $incomingBTC['hold_by'];
                                $holdSpend = $incomingBTC['hold_spend'];

                                unset($holdBy[array_search($outgoingBtcCacheTransaction['id'], $holdBy)],
                                $holdSpend[$outgoingBtcCacheTransaction['contact_id']]);

                                $holdBy    = empty($holdBy) ? null : $holdBy;
                                $holdSpend = empty($holdSpend) ? null : $holdSpend;

                                $incomingBTC['remaining']  += $incomingBTC['hold_spend'][$outgoingBtcCacheTransaction['contact_id']];
                                $incomingBTC['was_used']   = 0;
                                $incomingBTC['hold']       = empty($holdBy) ? 0 : 1;
                                $incomingBTC['hold_spend'] = $holdSpend;
                                $incomingBTC['hold_by']    = $holdBy;
                                $incomingBTC->save();
                            }
                        }
                        $outgoingBtcCacheTransaction->delete();
                    }

                    $innerOutgoingBtcCacheTransactions = $outgoingBtcCacheTransactions;
                    unset($innerOutgoingBtcCacheTransactions[$oKey]);
                    foreach ($innerOutgoingBtcCacheTransactions as $innerOutgoingBtcCacheTransaction) {
                        /**
                         * If this transaction as not been canceled we will check if is recorded has a complete
                         * transaction.
                         */
                        if (
                            $anchorCanceledTransaction === null &&
                            $innerOutgoingBtcCacheTransaction['contact_id'] === $outgoingBtcCacheTransaction['contact_id']
                        ) {
                            //Start
                            $anchorSelfTransaction = $innerOutgoingBtcCacheTransaction;
                            $incomingsToReserve    = IncomingBtc::where('hold_by', '!=', null)
                                ->get();

                            foreach ($incomingsToReserve as $incomingBTC) {
                                if (in_array($outgoingBtcCacheTransaction['id'], $incomingBTC['hold_by'])) {
                                    /**
                                     * Each incoming reserved by transaction ID is now assigned to an Outgoing BTC contact
                                     * ID.
                                     **/
                                    $reservedTo               = $incomingBTC->reserved_to;
                                    $reservedTo[]             = $innerOutgoingBtcCacheTransaction['contact_id'];
                                    $incomingBTC->reserved_to = $reservedTo;
                                    $incomingBTC->save();
                                }
                            }
                            $outgoingBtcCacheTransaction->delete();
                        }
                    }

                    if (
                        $anchorCanceledTransaction === null && $anchorSelfTransaction === null &&
                        $outgoingBtcCacheTransaction['is_hold'] === null
                    ) {

                        $this->holdingIncomingsBtc(
                            $outgoingBtcCacheTransaction['account_name'],
                            $outgoingBtcCacheTransaction['walletTransaction']['amount'],
                            $outgoingBtcCacheTransaction['id'],
                            $outgoingBtcCacheTransaction['contact_id']
                        );
                        $outgoingBtcCacheTransaction['is_hold'] = 1;
                        $outgoingBtcCacheTransaction->save();
                    }
                }
            }
        }

        return true;
    }


    /**
     * @param $accountName
     * @param $btcAmount
     * @param $holderID
     *
     * @param $contactID
     *
     * @return bool|null
     */
    private function holdingIncomingsBtc($accountName, $btcAmount, $holderID, $contactID): ?bool
    {
        $btcPurchases = IncomingBtc::where(
            [
                'was_used'     => 0,
                'account_name' => $accountName
            ]
        )
            ->where('remaining', '>', 0)
            ->orderBy('localbtc_released_date', 'ASC')
            ->get();

        /**
         * Se está recorriendo entre todas tus compras de BTC
         *
         * Nos traemos de la DB las prociones no usadas
         */
        $purchase = $btcPurchases[0];
        //Si a esta compra le queda más de 0
        //$btcAmount = BTC totales (liberados + fee)
        if ((string) $purchase->remaining >= (string) $btcAmount) {
            //$saleRate               = Precio BTC de venta
            //$purchaseRate           = Rate de la compra
            //$sale['amount_btc']     = Porción de BTC liberado
            //$sale['fee_btc']        = Local Fee

            $purchase->hold        = 1;
            $holdSpend             = $purchase->hold_spend;
            $holdSpend[$contactID] = $btcAmount;
            $purchase->hold_spend  = $holdSpend;
            $holdBy                = $purchase->hold_by;
            $holdBy[]              = $holderID;
            $purchase->hold_by     = $holdBy;

            $purchase->remaining -= $btcAmount;
            $purchase->save();

            return true;
        }

        //Cuando la porcion no puede pagar la venta total

        /**
         * $sale es una array que lleva los BTC liberados y el Fee
         * $purchase: Rate de compra, Remaining
         */

        return $this->dynamicHoldingIncomingsBtc($btcAmount, $purchase, 0, $btcPurchases, $holderID, $contactID);
    }

    /**
     * @param $btcAmount
     * @param $purchase
     * @param $key
     * @param $btcPurchases
     *
     * @param $holderID
     *
     * @param $contactID
     *
     * @return bool|null
     */
    private function dynamicHoldingIncomingsBtc(
        $btcAmount,
        $purchase,
        $key,
        $btcPurchases,
        $holderID,
        $contactID
    ): ?bool {
        $nLoan = $btcAmount - $purchase->remaining;
        $nLoan = round($nLoan, 8);
        unset($btcPurchases[$key]);

        $purchase->hold        = 1;
        $holdSpend             = $purchase->hold_spend;
        $holdSpend[$contactID] = $purchase->remaining;
        $purchase->hold_spend  = $holdSpend;
        $holdBy                = $purchase->hold_by;
        $holdBy[]              = $holderID;
        $purchase->hold_by     = $holdBy;

        $purchase->remaining = 0;
        $purchase->save();

        foreach ($btcPurchases as $nKey => $nPurchase) {
            if ((string) $nPurchase->remaining >= (string) $nLoan) {
                $nPurchase->hold       = 1;
                $holdSpend             = $nPurchase->hold_spend;
                $holdSpend[$contactID] = $nLoan;
                $nPurchase->hold_spend = $holdSpend;
                $holdBy                = $nPurchase->hold_by;
                $holdBy[]              = $holderID;
                $nPurchase->hold_by    = $holdBy;

                $nPurchase->remaining -= $nLoan;
                $nPurchase->save();

                return true;
            }

            if ($nPurchase->remaining > 0) {
                $nLoan -= $nPurchase->remaining;

                $nPurchase->hold       = 1;
                $holdSpend             = $nPurchase->hold_spend;
                $holdSpend[$contactID] = $nPurchase->remaining;
                $nPurchase->hold_spend = $holdSpend;
                $holdBy                = $nPurchase->hold_by;
                $holdBy[]              = $holderID;
                $nPurchase->hold_by    = $holdBy;

                $nPurchase->remaining = 0;
                $nPurchase->save();
            }
        }

        return null;
    }

    /**
     * @param $accountName
     * @param $btcAmount
     * @param $out
     *
     * @return bool|null
     */
    private function spendIncomingsBtc($accountName, $btcAmount, $out): ?bool
    {
        $out                     = OutgoingBtc::find($out);
        $out->incoming_btcs_used = $out->incoming_btcs_used ?? [];
        $btcPurchases            = IncomingBtc::where(
            [
                'was_used'     => 0,
                'account_name' => $accountName
            ]
        )
            ->orderBy('localbtc_released_date', 'ASC')
            ->get();

        /**
         * Se está recorriendo entre todas tus compras de BTC
         *
         * Nos traemos de la DB las prociones no usadas
         */
        $purchase = $btcPurchases[0];
        //Si a esta compra le queda más de 0
        //$btcAmount = BTC totales (liberados + fee)
        if ((string) $purchase['remaining'] >= (string) $btcAmount) {
            //$saleRate               = Precio BTC de venta
            //$purchaseRate           = Rate de la compra
            //$sale['amount_btc']     = Porción de BTC liberado
            //$sale['fee_btc']        = Local Fee

            $purchaseRate             = $purchase['usd_price'] / $purchase['amount_btc'];
            $purchaseRate             = round($purchaseRate, 2);
            $incomingBtcsUsed         = [];
            $incomingBtcsUsed['used'] = $btcAmount;
            $purchase['remaining']    -= $btcAmount;

            if ($purchase['reserved_to'] === null && $purchase['remaining'] <= 0) {
                $purchase['was_used'] = 1;
            }

            $incomingBtcsUsed['rate']            = $purchaseRate;
            $incomingBtcsUsedDB                  = $out->incoming_btcs_used;
            $incomingBtcsUsedDB[$purchase['id']] = $incomingBtcsUsed;
            $out->incoming_btcs_used             = $incomingBtcsUsedDB;
            $out->save();

            $purchase->save();

            $purchase->outgoings()->attach($out);

            return true;
        }

        //Cuando la porcion no puede pagar la venta total

        /**
         * $sale es una array que lleva los BTC liberados y el Fee
         * $purchase: Rate de compra, Remaining
         */
        $purchase->outgoings()->attach($out);

        return $this->dynamicSpendIncomingsBtc($btcAmount, $purchase, 0, $btcPurchases, $out);
    }

    /**
     * @param $btcAmount
     * @param $purchase
     * @param $key
     * @param $btcPurchases
     * @param $out
     *
     * @return bool|null
     */
    private function dynamicSpendIncomingsBtc($btcAmount, $purchase, $key, $btcPurchases, $out): ?bool
    {
        //$out                     = OutgoingBtc::find($out);
        $out->incoming_btcs_used = $out->incoming_btcs_used ?? [];
        $nLoan                   = $btcAmount - $purchase->remaining;
        unset($btcPurchases[$key]);


        $incomingBtcsUsed         = [];
        $incomingBtcsUsed['used'] = $purchase['remaining'];
        $purchase['remaining']    = 0;
        $purchase['was_used']     = 1;
        $purchase->save();

        $purchaseRate                        = $purchase['usd_price'] / $purchase['amount_btc'];
        $purchaseRate                        = round($purchaseRate, 2);
        $incomingBtcsUsed['rate']            = $purchaseRate;
        $incomingBtcsUsedDB                  = $out->incoming_btcs_used;
        $incomingBtcsUsedDB[$purchase['id']] = $incomingBtcsUsed;
        $out->incoming_btcs_used             = $incomingBtcsUsedDB;

        foreach ($btcPurchases as $nKey => $nPurchase) {
            $nPurchaseRate = $nPurchase['usd_price'] / $nPurchase['amount_btc'];
            $nPurchaseRate = round($nPurchaseRate, 2);

            if ((string) $nPurchase['remaining'] >= (string) $nLoan) {
                $incomingBtcsUsed         = [];
                $incomingBtcsUsed['used'] = round($nLoan, 8);
                $nPurchase['remaining']   -= $nLoan;

                if ($nPurchase['remaining'] <= 0) {
                    $nPurchase['remaining'] = 0;
                    $nPurchase['was_used']  = 1;
                }
                $incomingBtcsUsed['rate']             = $nPurchaseRate;
                $incomingBtcsUsedDB                   = $out->incoming_btcs_used;
                $incomingBtcsUsedDB[$nPurchase['id']] = $incomingBtcsUsed;
                $out->incoming_btcs_used              = $incomingBtcsUsedDB;

                $out->save();
                $nPurchase->save();

                //$out->incomings()->attach($nPurchase);

                return true;
            }

            if ($nPurchase['remaining'] > 0) {
                $incomingBtcsUsed         = [];
                $incomingBtcsUsed['used'] = $nPurchase['remaining'];
                $nLoan                    -= $nPurchase['remaining'];
                $nPurchase['remaining']   = 0;
                $nPurchase['was_used']    = 1;

                $incomingBtcsUsed['rate']             = $nPurchaseRate;
                $incomingBtcsUsedDB                   = $out->incoming_btcs_used;
                $incomingBtcsUsedDB[$nPurchase['id']] = $incomingBtcsUsed;
                $out->incoming_btcs_used              = $incomingBtcsUsedDB;

                $nPurchase->save();

                //$out->incomings()->attach($nPurchase);
            }
        }

        return null;
    }

    /**
     * Only record all outgoing BTC transactions.
     *
     * @param array $completeList
     *
     * @return bool|string
     */
    private function recordOutgoingBTC(array $completeList)
    {
        foreach ($completeList as $accountName => $account) {
            //Initial Nuclear Validations
            //Transaction with errors
            $errorTransaction = ErrorTransaction::where('account_name', $accountName)
                ->where('was_solved', 0)
                ->orderBy('localbtc_released_date', 'desc')
                ->first();

            if ($errorTransaction !== null) {
                continue;
                //TODO reporte de errores.
                //Go tp the next account. 'The system currently have a outgoing BTC transaction with errors';
            }

            $errorVerifyTransaction = ErrorTransaction::where('account_name', $accountName)
                ->where('was_verified', 0)
                ->orderBy('localbtc_released_date', 'desc')
                ->first();

            if ($errorVerifyTransaction !== null) {
                $errorVerifyTransaction['was_verified'] = 1;
                $errorVerifyTransaction->save();
            }

            $outgoingBtcTransactionsCache = OutgoingBtcCache::where(['status' => 0])
                ->orderBy('localbtc_released_date', 'asc')
                ->where([
                    'account_name' => $accountName,
                ])
                ->get()
                ->all();

            $activeContacts = ActiveContactCache::where([
                'account_name' => $accountName,
                'status'       => 0
            ])
                ->orderBy('anchor_date_localbtc', 'asc')
                ->get()
                ->all();

            //Equal ActiveContacts
            foreach ($activeContacts as $key => $activeContact) {
                $workingAC                           = $activeContact;
                $workingAC['localbtc_released_date'] = $workingAC['anchor_date_localbtc'];
                $activeContacts[$key]                = $workingAC;
                unset($workingAC);
            }

            $operationsToProcess = array_merge($outgoingBtcTransactionsCache, $activeContacts);
            usort($operationsToProcess, static function ($a, $b) {
                return $a['localbtc_released_date'] <=> $b['localbtc_released_date'];
            });

            //            var_dump($lastOutgoingBtcdate);
            //
            //            foreach ($outgoingBtcTransactionsCache as $item) {
            //                var_dump($item->contact_id);
            //            }
            //            die;

            foreach ($operationsToProcess as $outgoingBtcTransaction) {
                if (get_class($outgoingBtcTransaction) === 'App\OutgoingBtcCache') {
                    //Is some hold exist, stop all.
                    if ($outgoingBtcTransaction->is_hold !== null) {
                        //TODO reporte de errores.
                        continue 2;
                    }

                    if ($outgoingBtcTransaction['contactInfo'] !== null) {
                        $transactionReleasedDate = new Carbon($outgoingBtcTransaction['contactInfo']['funded_at']);
                    } else {
                        $transactionReleasedDate = new Carbon($outgoingBtcTransaction['localbtc_released_date']);
                    }
                    $utcReleasedDate = $transactionReleasedDate->format('Y-m-d H:i:s');
                    $releasedDate    = $transactionReleasedDate
                        ->setTimezone('EST')
                        ->format('Y-m-d H:i:s');

                    //Changed $releasedDate for $transactionReleasedDate in verification
                    $inc_data = IncomingBtc::totalRemaining(
                        $accountName,
                        $utcReleasedDate,
                        $outgoingBtcTransaction['contact_id']
                    );

                    if ($outgoingBtcTransaction['contactInfo'] !== null) {
                        //Start
                        if ((string) $outgoingBtcTransaction['walletTransaction']['amount'] > (string) $inc_data['total_remaining']) {
                            Mail::to([
                                'ignacio@isalcedo.com'
                            ])
                                ->send(new TransactionsErrorMessage('No enough BTC. Wallet Transaction date:' .
                                    $outgoingBtcTransaction['contactInfo']['contact_id']));

                            ErrorTransaction::create(
                                [
                                    'transaction_id'         => $outgoingBtcTransaction['contactInfo']['contact_id'],
                                    'amount_btc'             => $outgoingBtcTransaction['contactInfo']['amount_btc'],
                                    'json_data'              => $outgoingBtcTransaction['contactInfo'],
                                    'not_funds'              => 1,
                                    'released_date'          => $releasedDate,
                                    'localbtc_released_date' => $utcReleasedDate,
                                    'account_name'           => $accountName
                                ]
                            );

                            //TODO reporte de errores.
                            continue 2;
                        }

                        if ($this->incomingsReview(
                            $outgoingBtcTransaction['walletTransaction']['amount'],
                            $accountName,
                            $outgoingBtcTransaction['contact_id']
                        ) === false) {
                            Mail::to([
                                'ignacio@isalcedo.com'
                            ])->send(new TransactionsErrorMessage('No USD Price. Wallet Transaction date:' .
                                $outgoingBtcTransaction['contactInfo']['contact_id']));

                            ErrorTransaction::create(
                                [
                                    'transaction_id'         => $outgoingBtcTransaction['contactInfo']['contact_id'],
                                    'amount_btc'             => $outgoingBtcTransaction['contactInfo']['amount_btc'],
                                    'json_data'              => $outgoingBtcTransaction['contactInfo'],
                                    'not_usd_price'          => 1,
                                    'released_date'          => $releasedDate,
                                    'localbtc_released_date' => $outgoingBtcTransaction['localbtc_released_date'],
                                    'account_name'           => $accountName
                                ]
                            );

                            //TODO reporte de errores.
                            continue 2;
                        }

                        $fee       = null;
                        $amountBTC = $outgoingBtcTransaction['contactInfo']['amount_btc'];

                        if (
                            $outgoingBtcTransaction['walletTransaction']['amount'] >
                            $outgoingBtcTransaction['contactInfo']['amount_btc']
                        ) {
                            $fee = $outgoingBtcTransaction['contactInfo']['fee_btc'];
                        }

                        $newTransaction              = [
                            'bank_name'              => '',
                            'transaction_id'         => $outgoingBtcTransaction['contactInfo']['contact_id'],
                            'amount'                 => $outgoingBtcTransaction['contactInfo']['amount'],
                            'amount_btc'             => $amountBTC,
                            'fee_btc'                => $fee,
                            'msg'                    => 'Bank name must be set by an auditor.',
                            'currency'               => $outgoingBtcTransaction['contactInfo']['currency'],
                            'json_data'              => $outgoingBtcTransaction['contactInfo'],
                            'released_date'          => $releasedDate,
                            'localbtc_released_date' => $outgoingBtcTransaction['localbtc_released_date'],
                            'account_name'           => $accountName,
                            'type'                   => 'Incoming'
                        ];
                        $newTransaction['usd_price'] = null;

                        if ($outgoingBtcTransaction['contactInfo']['currency'] === 'VES') {
                            $fundedDate                  = new Carbon($outgoingBtcTransaction['contactInfo']['funded_at']);
                            $fundedDate                  = $fundedDate->format('Y-m-d H:i:s');
                            $bitstamp                    = BitstampData::where(
                                'created_at',
                                '>=',
                                $fundedDate
                            )->first();
                            $newTransaction['usd_price'] = $bitstamp['price'] *
                                $outgoingBtcTransaction['contactInfo']['amount_btc'];
                        } elseif ($outgoingBtcTransaction['contactInfo']['currency'] === 'USD') {
                            $newTransaction['usd_price'] = $outgoingBtcTransaction['contactInfo']['amount'];
                        } else {
                            $newTransaction['error'] = 1;
                        }

                        $newTransaction = Transaction::create($newTransaction);

                        $newOutgoingBtcTransaction = OutgoingBtc::create(
                            [
                                'transaction_id'         => $newTransaction->id,
                                'amount_btc'             => $newTransaction->amount_btc,
                                'usd_price'              => $newTransaction->usd_price,
                                'released_date'          => $releasedDate,
                                'localbtc_released_date' => $newTransaction->localbtc_released_date,
                                'fee_btc'                => $fee ?? 0,
                                'total_btc'              => $outgoingBtcTransaction['walletTransaction']['amount'],
                                'profit'                 => 0,
                                'account_name'           => $accountName,
                                'contact_id'             => $outgoingBtcTransaction['contactInfo']['contact_id']
                            ]
                        );

                        $newOutgoingBtcTransaction->profit = $this->calculatingProfit(
                            $outgoingBtcTransaction,
                            $newOutgoingBtcTransaction->id,
                            $accountName
                        );

                        $outgoingBtcTransaction->status = 1;
                        $outgoingBtcTransaction->save();
                        $newOutgoingBtcTransaction->save();
                    }

                    if ($outgoingBtcTransaction['contactInfo'] === null) {
                        if ((string) $outgoingBtcTransaction['walletTransaction']['amount'] > (string) $inc_data['total_remaining']) {
                            Mail::to([
                                'ignacio@isalcedo.com'
                            ])->send(new TransactionsErrorMessage('No enough BTC. Wallet Transaction date:' .
                                $outgoingBtcTransaction['walletTransaction']['created_at']));

                            ErrorTransaction::create(
                                [
                                    'transaction_id'         => 'No LocalBTC ID',
                                    'amount_btc'             => $outgoingBtcTransaction['walletTransaction']['amount'],
                                    'json_data'              => $outgoingBtcTransaction['walletTransaction'],
                                    'not_funds'              => 1,
                                    'released_date'          => $releasedDate,
                                    'localbtc_released_date' => $outgoingBtcTransaction['localbtc_released_date'],
                                    'account_name'           => $accountName
                                ]
                            );

                            //TODO reporte de errores.
                            continue 2;
                        }

                        if ($this->incomingsReview(
                            $outgoingBtcTransaction['walletTransaction']['amount'],
                            $accountName,
                            $outgoingBtcTransaction['contact_id']
                        ) === false) {
                            Mail::to([
                                'ignacio@isalcedo.com'
                            ])->send(new TransactionsErrorMessage('No USD Price. Wallet Transaction date:' .
                                $outgoingBtcTransaction['walletTransaction']['created_at']));

                            ErrorTransaction::create(
                                [
                                    'transaction_id'         => 'No LocalBTC ID',
                                    'amount_btc'             => $outgoingBtcTransaction['walletTransaction']['amount'],
                                    'json_data'              => $outgoingBtcTransaction['walletTransaction'],
                                    'not_usd_price'          => 1,
                                    'released_date'          => $releasedDate,
                                    'localbtc_released_date' => $outgoingBtcTransaction['localbtc_released_date'],
                                    'account_name'           => $accountName
                                ]
                            );

                            //TODO reporte de errores.
                            continue 2;
                        }

                        $newTransaction = [
                            'bank_name'              => '',
                            'transaction_id'         => '',
                            'amount'                 => 0,
                            'amount_btc'             => $outgoingBtcTransaction['walletTransaction']['amount'],
                            'msg'                    => 'Bank name must be set by an auditor.',
                            'currency'               => '',
                            'json_data'              => [],
                            'released_date'          => $releasedDate,
                            'localbtc_released_date' => $outgoingBtcTransaction['localbtc_released_date'],
                            'account_name'           => $accountName,
                            'type'                   => 'Incoming',
                            'is_manual'              => 1,
                            'usd_price'              => null,
                            'error'                  => 1
                        ];

                        if (isset($outgoingBtcTransaction['walletTransaction']['fee'])) {
                            $btcToSpend                = $outgoingBtcTransaction['walletTransaction']['amount'] +
                                $outgoingBtcTransaction['walletTransaction']['fee'];
                            $newTransaction['fee_btc'] = $outgoingBtcTransaction['walletTransaction']['fee'];
                            $newTransaction            = Transaction::create($newTransaction);
                            $newOutgoingBtcTransaction = OutgoingBtc::create(
                                [
                                    'transaction_id'         => $newTransaction->id,
                                    'amount_btc'             => $newTransaction['amount_btc'],
                                    'usd_price'              => $newTransaction->usd_price,
                                    'released_date'          => $releasedDate,
                                    'localbtc_released_date' => $newTransaction->localbtc_released_date,
                                    'fee_btc'                => $outgoingBtcTransaction['walletTransaction']['fee'],
                                    'total_btc'              => $newTransaction->amount_btc,
                                    'profit'                 => 0,
                                    'account_name'           => $accountName,
                                    'contact_id'             => -1
                                ]
                            );
                        } else {
                            $btcToSpend                = $outgoingBtcTransaction['walletTransaction']['amount'];
                            $newTransaction            = Transaction::create($newTransaction);
                            $newOutgoingBtcTransaction = OutgoingBtc::create(
                                [
                                    'transaction_id'         => $newTransaction->id,
                                    'amount_btc'             => $newTransaction->amount_btc,
                                    'usd_price'              => $newTransaction->usd_price,
                                    'released_date'          => $releasedDate,
                                    'localbtc_released_date' => $newTransaction->localbtc_released_date,
                                    'fee_btc'                => 0,
                                    'total_btc'              => $outgoingBtcTransaction['walletTransaction']['amount'],
                                    'profit'                 => 0,
                                    'account_name'           => $accountName,
                                    'contact_id'             => -1
                                ]
                            );
                        }

                        //A estas transacciones se les debe dar el precio a mano y diversas cosas para calcularle utilidad
                        //Igual deberían gastar los fragmentos
                        $newOutgoingBtcTransaction->profit = $this->spendIncomingsBtc(
                            $accountName,
                            $btcToSpend,
                            $newOutgoingBtcTransaction->id
                        );

                        $outgoingBtcTransaction->status = 1;
                        $outgoingBtcTransaction->save();


                        //                    $newOutgoingBtcTransaction->profit = $this->calculatingProfit(
                        //                        $outgoingBtcTransaction['contactInfo'],
                        //                        $newOutgoingBtcTransaction->id,
                        //                        $accountName
                        //                    );
                        //                    $newOutgoingBtcTransaction->save();
                    }
                } else {
                    $apiHelper = new ApiHelper;
                    $apiHelper::holdingIncomingsBtc(
                        $accountName,
                        $outgoingBtcTransaction
                    );
                }
            }
        }

        return true;
    }

    /**
     * Only record all incoming BTC transactions.
     *
     * @param array $completeList
     *
     * @return bool
     */
    public function recordIncomingBTC(array $completeList): bool
    {
        foreach ($completeList as $accountName => $account) {
            $lastIncomingBtcTransaction = IncomingBtc::where('account_name', $accountName)
                ->orderBy('localbtc_released_date', 'desc')
                ->first();
            $lastIncommingBtcdate       = strtotime('2019-03-01T05:00:00+00:00');

            if ($lastIncomingBtcTransaction !== null) {
                $lastIncommingBtcdate = strtotime($lastIncomingBtcTransaction->localbtc_released_date);
            }

            foreach ($account['incomingBTC'] as $incomingBtcTransaction) {
                if (
                    $incomingBtcTransaction['contactInfo'] !== null &&
                    $lastIncommingBtcdate < strtotime($incomingBtcTransaction['contactInfo']['released_at'])
                ) {
                    //                    Right now ignore FiatWallets
                    //                    if ($outgoingBtcTransaction['contactInfo']['currency'] === 'USD') {
                    //                        $wallet = CurrencyWallet::where('currency', 'USD')->first();
                    //                        $wallet->outgoingTransaction($outgoingBtcTransaction['contactInfo']['amount']);
                    //                    }

                    $transactionReleasedDate = new Carbon($incomingBtcTransaction['contactInfo']['released_at']);
                    $localbtcReleasedDate    = $transactionReleasedDate->format('Y-m-d H:i:s');
                    $releasedDate            = $transactionReleasedDate
                        ->setTimezone('EST')
                        ->format('Y-m-d H:i:s');

                    $newTransaction = [
                        'bank_name'              => '',
                        'transaction_id'         => $incomingBtcTransaction['contactInfo']['contact_id'],
                        'amount'                 => $incomingBtcTransaction['contactInfo']['amount'],
                        'amount_btc'             => $incomingBtcTransaction['walletTransaction']['amount'],
                        'msg'                    => 'Bank name must be set by an auditor.',
                        'currency'               => $incomingBtcTransaction['contactInfo']['currency'],
                        'json_data'              => $incomingBtcTransaction['contactInfo'],
                        'released_date'          => $releasedDate,
                        'localbtc_released_date' => $localbtcReleasedDate,
                        'type'                   => 'Outgoing',
                        'account_name'           => $accountName
                    ];

                    $newTransaction['usd_price'] = null;
                    if ($incomingBtcTransaction['contactInfo']['currency'] === 'VES') {
                        $bitstamp                    = BitstampData::getNow();
                        $newTransaction['usd_price'] = $bitstamp *
                            $incomingBtcTransaction['walletTransaction']['amount'];
                    } elseif ($incomingBtcTransaction['contactInfo']['currency'] === 'USD') {
                        $newTransaction['usd_price'] = $incomingBtcTransaction['contactInfo']['amount'];
                    } else {
                        $newTransaction['error'] = 1;
                    }

                    $newTransaction = Transaction::create($newTransaction);
                    IncomingBtc::create([
                        'transaction_id'         => $newTransaction->id,
                        'amount_btc'             => $incomingBtcTransaction['walletTransaction']['amount'],
                        'usd_price'              => $newTransaction->usd_price,
                        'released_date'          => $releasedDate,
                        'localbtc_released_date' => $localbtcReleasedDate,
                        'remaining'              => $incomingBtcTransaction['walletTransaction']['amount'],
                        'account_name'           => $accountName,
                        'hold'                   => 0,
                        'hold_spend'             => null,
                        'hold_by'                => null
                    ]);
                }

                if (
                    $incomingBtcTransaction['contactInfo'] === null &&
                    $lastIncommingBtcdate < strtotime($incomingBtcTransaction['walletTransaction']['created_at'])
                ) {
                    $transactionReleasedDate = new Carbon($incomingBtcTransaction['walletTransaction']['created_at']);
                    $localbtcReleasedDate    = $transactionReleasedDate->format('Y-m-d H:i:s');
                    $releasedDate            = $transactionReleasedDate
                        ->setTimezone('EST')
                        ->format('Y-m-d H:i:s');

                    $newTransaction = [
                        'bank_name'              => '',
                        'transaction_id'         => '',
                        'amount'                 => 0,
                        'amount_btc'             => $incomingBtcTransaction['walletTransaction']['amount'],
                        'msg'                    => 'Only Wallet Transaction. Needs more info',
                        'currency'               => '',
                        'json_data'              => [],
                        'released_date'          => $releasedDate,
                        'localbtc_released_date' => $localbtcReleasedDate,
                        'type'                   => 'Outgoing',
                        'account_name'           => $accountName,
                        'is_manual'              => 1
                    ];

                    //TODO watch info in description
                    $newTransaction['usd_price'] = null;
                    $newTransaction['error']     = 1;
                    $newTransaction              = Transaction::create($newTransaction);

                    IncomingBtc::create([
                        'transaction_id'         => $newTransaction->id,
                        'amount_btc'             => $incomingBtcTransaction['walletTransaction']['amount'],
                        'usd_price'              => $newTransaction->usd_price,
                        'released_date'          => $releasedDate,
                        'localbtc_released_date' => $localbtcReleasedDate,
                        'remaining'              => $incomingBtcTransaction['walletTransaction']['amount'],
                        'account_name'           => $accountName,
                        'hold'                   => 0,
                        'hold_spend'             => null,
                        'hold_by'                => null
                    ]);
                }
            }
        }

        return true;
    }

    /**
     * @return string|void
     */
    public function recordTransactions()
    {
        //$last_t_date = Carbon::now();

        $usernames = Credential::get();
        $names     = [];
        foreach ($usernames as $name) {
            array_push($names, $name->username);
        }

        //Cuando el outgoing no se ha solucionado.
        $error_rep = ErrorTransaction::where('was_solved', 0)->orderBy('id', 'desc')->first();

        if (isset($error_rep)) {
            return 'error 1';
        }

        $error_rep_2 = ErrorTransaction::where('was_verified', 0)->orderBy('id', 'desc')->first();

        if (isset($error_rep_2)) {
            $last_t_date                 = new Carbon($error_rep_2->json_data['data']['released_at']);
            $error_rep_2['was_verified'] = 1;
            $error_rep_2->save();
        } else {
            $last_t_date = Carbon::now();
        }

        $contacts = LocalTrades::getTransactionListV2($last_t_date);

        //  $verify_error = ErrorTransaction::where('was_solved', 0)->first();
        /*  if (isset($verify_error)) {
            return 'error';
          } */

        $last_transaction = Transaction::orderBy('id', 'desc')->first();

        if (isset($last_transaction)) {
            $last_date = new Carbon($last_transaction->released_date);
        } else {
            $last_date = Carbon::now();
            $last_date = $last_date->subDays(30);
        }
        //  return dd($last_transaction);
        //getting bitstamp
        $bitstamp = BitstampData::getNow();

        //getting data

        //$contacts = LocalTrades::getCompletedTrades();

        //return dd($contacts);

        foreach ($contacts as $data) {

            $carbon = new Carbon($data['data']['released_at']);

            if ($carbon > $last_date) {
                if (!in_array($data['data']['buyer']['username'], $names)) {
                    //    return 'outgoing';
                    $inc_data = IncomingBtc::totalRemaining();

                    if ($data['data']['amount_btc'] > $inc_data['total_remaining']) {

                        Mail::to([
                            'ivanlecointere@gmail.com',
                            'ivan_lecointere@hotmail.com'
                        ])->send(new TransactionsErrorMessage($data['data']['contact_id']));

                        ErrorTransaction::create(
                            [
                                'transaction_id' => $data['data']['contact_id'],
                                'amount_btc'     => $data['data']['amount_btc'],
                                'json_data'      => $data,
                                'not_funds'      => 1
                            ]
                        );

                        return 'error';
                    }

                    if ($this->incomingsReview($data['data']['amount_btc']) == 1) {

                        Mail::to([
                            'ivanlecointere@gmail.com',
                            'ivan_lecointere@hotmail.com'
                        ])->send(new TransactionsErrorMessage($data['data']['contact_id']));

                        ErrorTransaction::create(
                            [
                                'transaction_id' => $data['data']['contact_id'],
                                'amount_btc'     => $data['data']['amount_btc'],
                                'json_data'      => $data,
                                'not_usd_price'  => 1
                            ]
                        );

                        return 'error';
                    }
                }

                //debiting from wallet
                if (in_array($data['data']['buyer']['username'], $names) && $data['data']['currency'] == 'USD') {
                    //return 'is incoming';
                    $wallet = CurrencyWallet::where('currency', 'USD')->first();

                    $wallet->outgoingTransaction($data['data']['amount']);
                } elseif (!in_array($data['data']['buyer']['username'], $names) && $data['data']['currency'] == 'USD') {
                    //return 'is ourgoing';
                    $wallet = CurrencyWallet::where('currency', 'USD')->first();

                    $wallet->incomingTransaction($data['data']['amount']);
                }

                $transaction_released = new Carbon($data['data']['released_at']);

                //transaction data
                $new_transaction = [
                    'bank_name'      => '',
                    'transaction_id' => $data['data']['contact_id'],
                    'amount'         => $data['data']['amount'],
                    'msg'            => 'Bank name must be set by an auditor.',
                    'currency'       => $data['data']['currency'],
                    'json_data'      => $data,
                    'released_date'  => $transaction_released->format('Y-m-d H:i:s')
                ];

                if (in_array($data['data']['buyer']['username'], $names)) {
                    $new_transaction['type']         = 'Outgoing';
                    $new_transaction['account_name'] = $data['data']['buyer']['username'];
                } else {
                    $new_transaction['type']         = 'Incoming';
                    $new_transaction['account_name'] = $data['data']['seller']['username'];
                }

                if ($data['data']['currency'] == 'VES') {
                    $new_transaction['usd_price'] = $bitstamp * $data['data']['amount_btc'];
                } else {
                    $new_transaction['error'] = 1;
                }

                //recording
                $new_t = Transaction::create($new_transaction);

                if (in_array($data['data']['buyer']['username'], $names)) {
                    IncomingBtc::create([
                        'transaction_id' => $new_t->id,
                        'amount_btc'     => $data['data']['amount_btc'],
                        'usd_price'      => $new_t->usd_price,
                        'released_date'  => $transaction_released->format('Y-m-d H:i:s'),
                        'remaining'      => $data['data']['amount_btc']
                    ]);
                } else {
                    $outBtc = OutgoingBtc::create(
                        [
                            'transaction_id' => $new_t->id,
                            'amount_btc'     => $data['data']['amount_btc'],
                            'usd_price'      => $new_t->usd_price,
                            'released_date'  => $transaction_released->format('Y-m-d H:i:s'),
                            'fee_btc'        => $data['data']['fee_btc'],
                            'total_btc'      => $data['data']['amount_btc'] + $data['data']['fee_btc'],
                            'profit'         => 0
                        ]
                    );

                    $outBtc->profit = $this->calculatingProfit($data['data'], $outBtc->id);
                    $outBtc->save();
                }
            }
        }

        return dd($contacts);
    }


    /**
     * @param array $sale
     * @param       $out
     * @param       $accountName
     *
     * @return float
     */
    private function calculatingProfit($sale, $out, $accountName): float
    {
        $out = OutgoingBtc::find($out);

        $totalBtcOut = $sale['walletTransaction']['amount']; //Fee is included in this value + $sale['fee_btc'];

        $btcPurchases = IncomingBtc::where(
            'reserved_to',
            'like',
            '%' . $out->contact_id . '%'
        )
            ->orderBy('localbtc_released_date', 'ASC')
            ->get();


        if ($btcPurchases->count() === 0) {
            $btcPurchases = IncomingBtc::where(
                [
                    'was_used'     => 0,
                    'account_name' => $accountName
                ]
            )
                ->where('remaining', '>', 0)
                ->orderBy('localbtc_released_date', 'ASC')
                ->get();
        }

        //        if ((string)$sale['contactInfo']['amount_btc'] === (string)$sale['walletTransaction']['amount']) {
        //            $thisContactInfo               = $sale['contactInfo'];
        //            $thisSaleAmountBTC             = $sale['contactInfo']['amount_btc'] - $sale['contactInfo']['fee_btc'];
        //            $thisContactInfo['amount_btc'] = $thisSaleAmountBTC;
        //            $sale['contactInfo']           = $thisContactInfo;
        //        }

        //condicional de currency, calcular precio en dolares basados en bitstamp
        if ($sale['contactInfo']['currency'] === 'USD') {
            $saleRate = $sale['contactInfo']['amount'] / $sale['contactInfo']['amount_btc'];
        } else {
            $tempAmount = $sale['contactInfo']['amount_btc'] * BitstampData::getNow();
            $saleRate   = $tempAmount / $sale['contactInfo']['amount_btc'];
        }
        $saleRate = round($saleRate, 2);

        $out->incoming_btcs_used = $out->incoming_btcs_used ?? [];

        /**
         * Se está recorriendo entre todas tus compras de BTC
         *
         * Nos traemos de la DB las prociones no usadas
         */
        $purchase     = $btcPurchases[0];
        $purchaseRate = $purchase['usd_price'] / $purchase['amount_btc'];
        $purchaseRate = round($purchaseRate, 2);
        //If is not a reserved incoming it uses the remaining.
        $remainingToUse = $purchase['remaining'];
        if ($purchase['reserved_to'] !== null && in_array($out->contact_id, $purchase['reserved_to'])) {
            $remainingToUse = $purchase['hold_spend'][$out->contact_id];
        }
        //Si a esta compra le queda más de 0
        //$totalBtcOut = BTC totales (liberados + fee)
        if ((string) $remainingToUse >= (string) $totalBtcOut) {
            //$saleRate               = Precio BTC de venta
            //$purchaseRate           = Rate de la compra
            //$sale['amount_btc']     = Porción de BTC liberado
            //$sale['fee_btc']        = Local Fee

            $incomingBtcsUsed = [];

            if ($purchase->reserved_to !== null && in_array($out->contact_id, $purchase['reserved_to'])) {
                $holdSpend                   = $purchase['hold_spend'];
                $holdSpend[$out->contact_id] = 0;
                $incomingBtcsUsed['used']    = $purchase['hold_spend'][$out->contact_id];
                $purchase['hold_spend']      = $holdSpend;
            } else {
                $purchase['remaining']    -= $totalBtcOut;
                $incomingBtcsUsed['used'] = $totalBtcOut;
            }

            if ($purchase['remaining'] <= 0) {
                $purchase['remaining'] = 0;
                if (is_array($purchase['hold_spend']) && array_sum($purchase['hold_spend']) <= 0) {
                    $purchase['was_used'] = 1;
                }
                if (!is_array($purchase['hold_spend'])) {
                    $purchase['was_used'] = 1;
                }
            }

            $incomingBtcsUsed['rate']            = $purchaseRate;
            $incomingBtcsUsedDB                  = $out->incoming_btcs_used;
            $incomingBtcsUsedDB[$purchase['id']] = $incomingBtcsUsed;
            $out->incoming_btcs_used             = $incomingBtcsUsedDB;
            $out->save();

            $purchase->save();
            $purchase->outgoings()->attach($out);

            if ((string) $sale['contactInfo']['amount_btc'] === (string) $sale['walletTransaction']['amount']) {
                $profit = ($saleRate - $purchaseRate) * $sale['contactInfo']['amount_btc'];
            } else {
                $profit = ($saleRate - $purchaseRate) * $sale['contactInfo']['amount_btc'] -
                    ($sale['contactInfo']['fee_btc'] * $purchaseRate);
            }

            return $profit;

            /**
             * Se debe actualizar el modelo de la compra de BTC, con el remaining
             * Se debe actualizar el modelo de la compra si el remaining es 0 a USADA
             */
        }

        //Cuando la porcion no puede pagar la venta total

        /**
         * $sale es una array que lleva los BTC liberados y el Fee
         * $purchase: Rate de compra, Remaining
         */
        $purchase->outgoings()->attach($out);

        return $this->calculateDynamicProfit($sale, $purchase, 0, $btcPurchases, $out->id);
    }

    /**
     * @param $sale
     * @param $purchase
     * @param $key
     * @param $btcPurchases
     * @param $out
     *
     * @return float|null
     */
    private function calculateDynamicProfit($sale, $purchase, $key, $btcPurchases, $out): ?float
    {
        $out            = OutgoingBtc::find($out);
        $remainingToUse = $purchase['remaining'];
        if ($purchase['reserved_to'] !== null && in_array($out->contact_id, $purchase['reserved_to'])) {
            $remainingToUse = $purchase['hold_spend'][$out->contact_id];
        }

        $nLoan       = (float) $sale['walletTransaction']['amount'] - (float) $remainingToUse; //(202-20) = 182 //2 fee
        $nLoan       = round($nLoan, 8);
        $extraProfit = 0;
        $feeCost     = 0;
        //En algunos casos de venta, no debemos considerar el Fee en la operacion. Cuando vendemos a anunciante, por ejemplo.
        $considerFee = false;
        if ((string) $sale['contactInfo']['amount_btc'] < (string) $sale['walletTransaction']['amount']) {
            $considerFee = true;
        }

        if ($sale['contactInfo']['currency'] === 'USD') {
            $saleRate = $sale['contactInfo']['amount'] / $sale['contactInfo']['amount_btc'];
        } else {
            $tempAmount = $sale['contactInfo']['amount_btc'] * BitstampData::getNow();
            $saleRate   = $tempAmount / $sale['contactInfo']['amount_btc'];
        }
        $saleRate     = round($saleRate, 2);
        $purchaseRate = $purchase['usd_price'] / $purchase['amount_btc'];
        $purchaseRate = round($purchaseRate, 2);
        unset($btcPurchases[$key]);

        $out->incoming_btcs_used = $out->incoming_btcs_used ?? [];

        foreach ($btcPurchases as $nKey => $nPurchase) {
            $nPurchaseRate  = $nPurchase['usd_price'] / $nPurchase['amount_btc'];
            $nPurchaseRate  = round($nPurchaseRate, 2);
            $remainingToUse = $nPurchase['remaining'];
            if ($nPurchase['reserved_to'] !== null && in_array($out->contact_id, $nPurchase['reserved_to'])) {
                $remainingToUse = $nPurchase['hold_spend'][$out->contact_id];
            }
            /** @var IncomingBtc $nPurchase */
            /**
             * Si la fracción posee más de 0 BTC y además 11964,75 732095.7
             * más que el préstamo requerido
             */
            if ((string) $remainingToUse >= (string) $nLoan) {
                $incomingBtcsUsed = [];
                //Si el prestamo es mayor o igual al Fee de LocalBTC
                $feeCalculation = 0;
                if ($considerFee) {
                    if ($nLoan >= $sale['contactInfo']['fee_btc']) {
                        //El balance del préstamo es igual al préstamo menos el Fee de LocalBtc
                        $loanBalance = $nLoan - $sale['contactInfo']['fee_btc'];
                        $loanBalance = round($loanBalance, 8);
                        /** Se le agrega al costo extra el costo del fragmento aportado a la venta
                         * según el costo de este fragmento
                         **/
                        $extraProfit += ($saleRate - $nPurchaseRate) * $loanBalance;
                        //El costo del fee estará relacionado con el costro de esta fracción
                        $feeCalculation = $sale['contactInfo']['fee_btc'] * $nPurchaseRate;
                    } else {
                        /**
                         * Habrán casos donde no se está pidiendo BTC a la otra porción por el fee completo
                         * sino por parte del Fee. Solo esa parte que se pide tiene el costo de la fracción que presta.
                         */
                        //El costo del préstamo estará relacionado con el costro de esta fracción
                        $nLoanCost = $nLoan * $nPurchaseRate;
                        $nLoanCost = round($nLoanCost, 2);
                        //El costo del Fee va estar relacionado al costo de la fracción que pide
                        $feeCost        += $nLoanCost;
                        $feeCalculation = round($feeCost, 2);
                    }
                } else {
                    $extraProfit += ($saleRate - $nPurchaseRate) * $nLoan;
                }

                //La fracción que pide aporta enteramente su cantidad de BTC disponible
                if ($purchase['reserved_to'] !== null && in_array($out->contact_id, $purchase['reserved_to'])) {
                    $holdSpend                   = $purchase['hold_spend'];
                    $holdSpend[$out->contact_id] = 0;
                    $incomingBtcsUsed['used']    = $purchase['hold_spend'][$out->contact_id];
                    $saleBalance                 = $purchase['hold_spend'][$out->contact_id];
                    $purchase['hold_spend']      = $holdSpend;
                } else {
                    $saleBalance              = $purchase['remaining'];
                    $incomingBtcsUsed['used'] = $purchase['remaining'];
                    $purchase['remaining']    = 0;
                }

                $incomingBtcsUsed['rate']            = $purchaseRate;
                $incomingBtcsUsedDB                  = $out->incoming_btcs_used;
                $incomingBtcsUsedDB[$purchase['id']] = $incomingBtcsUsed;
                $out->incoming_btcs_used             = $incomingBtcsUsedDB;
                //$saleBalance debe ser igual al total de BTC disponibles de la fracción
                //A la fracción que presta se le reduce el préstamo, puede ser cero (0) o un valor decimal dado
                $incomingBtcsUsed = [];
                if ($nPurchase['reserved_to'] !== null && in_array($out->contact_id, $nPurchase['reserved_to'])) {
                    $holdSpend                   = $nPurchase['hold_spend'];
                    $holdSpend[$out->contact_id] = 0;
                    $incomingBtcsUsed['used']    = $nPurchase['hold_spend'][$out->contact_id];
                    $nPurchase['hold_spend']     = $holdSpend;
                } else {
                    $nPurchase['remaining']   -= $nLoan;
                    $incomingBtcsUsed['used'] = $nLoan;
                }
                $incomingBtcsUsed['rate']             = $nPurchaseRate;
                $incomingBtcsUsedDB                   = $out->incoming_btcs_used;
                $incomingBtcsUsedDB[$nPurchase['id']] = $incomingBtcsUsed;
                $out->incoming_btcs_used              = $incomingBtcsUsedDB;
                //Se actualiza la fracción en la Array principal
                //Escribir la DB

                if ($purchase['remaining'] <= 0) {
                    $purchase['remaining'] = 0;

                    if (is_array($purchase['hold_spend']) && array_sum($purchase['hold_spend']) <= 0) {
                        $purchase['was_used'] = 1;
                    }
                    if (!is_array($purchase['hold_spend'])) {
                        $purchase['was_used'] = 1;
                    }
                }

                if ($nPurchase['remaining'] <= 0) {
                    $nPurchase['remaining'] = 0;

                    if (is_array($nPurchase['hold_spend']) && array_sum($nPurchase['hold_spend']) <= 0) {
                        $nPurchase['was_used'] = 1;
                    }
                    if (!is_array($nPurchase['hold_spend'])) {
                        $nPurchase['was_used'] = 1;
                    }
                }

                //                if ($nPurchase->remaining <= 0) {
                //                    $nPurchase->was_used = 1;
                //                }

                $purchase->save();
                $nPurchase->save();
                $out->save();

                $out->incomings()->attach($purchase);
                $out->incomings()->attach($nPurchase);

                //SaleBalance siempre debe ser la contribución de la fracción inicial
                return ((($saleRate - $purchaseRate) * $saleBalance) + $extraProfit) - $feeCalculation;
            }
            /**
             * Si la fracción posee más de 0 BTC pero
             * menos que el préstamo requerido
             */
            if ($remainingToUse > 0) {
                $incomingBtcsUsed = [];
                //Se sumará al costo extra el costo de esta fracción.
                $extraProfit += ($saleRate - $nPurchaseRate) * $remainingToUse;
                //Se reducirá de la deuda: lo aportado por esta fracción
                $nLoan -= $remainingToUse; //182 - 181 = 1
                //Se reducirá de la venta inicial: lo aportado por esta fracción
                //Fragmento aportado al fee, en caso de que ocurra.
                $feeFragment                   = $remainingToUse - $sale['contactInfo']['amount_btc'];
                $saleContactInfo               = $sale['contactInfo'];
                $saleContactInfo['amount_btc'] -= $remainingToUse; //19

                if ($saleContactInfo['amount_btc'] < 0) {
                    $saleContactInfo['amount_btc'] = 0;
                    if ($considerFee) {
                        $saleContactInfo['fee_btc'] -= $feeFragment;
                        $feeCost                    += $feeFragment * $nPurchaseRate;
                    }
                }

                $sale['contactInfo'] = $saleContactInfo;

                //Los BTC restantes de esta fracción bajan a 0 pues prestó todo
                if ($nPurchase['reserved_to'] !== null && in_array($out->contact_id, $nPurchase['reserved_to'])) {
                    $holdSpend                   = $nPurchase['hold_spend'];
                    $holdSpend[$out->contact_id] = 0;
                    $incomingBtcsUsed['used']    = $nPurchase['hold_spend'][$out->contact_id];
                    $nPurchase['hold_spend']     = $holdSpend;
                } else {
                    $incomingBtcsUsed['used'] = $nPurchase['remaining'];
                    $nPurchase['remaining']   = 0;
                }
                //Se actualiza esta fracción en la Array principal
                if ($nPurchase['remaining'] <= 0) {
                    $nPurchase['remaining'] = 0;

                    if (is_array($nPurchase['hold_spend']) && array_sum($nPurchase['hold_spend']) <= 0) {
                        $nPurchase['was_used'] = 1;
                    }
                    if (!is_array($nPurchase['hold_spend'])) {
                        $nPurchase['was_used'] = 1;
                    }
                }

                $incomingBtcsUsed['rate']             = $nPurchaseRate;
                $incomingBtcsUsedDB                   = $out->incoming_btcs_used;
                $incomingBtcsUsedDB[$nPurchase['id']] = $incomingBtcsUsed;
                $out->incoming_btcs_used              = $incomingBtcsUsedDB;

                //$nPurchase->was_used = 1;
                $nPurchase->save();
            }

            $out->incomings()->attach($nPurchase);
        }

        return null;
    }

    /**
     * @param $amount
     *
     * @param $accountName
     * @param $contactID
     *
     * @return bool
     */
    private function incomingsReview($amount, $accountName, $contactID): bool
    {
        $incomingTransactions = IncomingBtc::where([
            'was_used'     => 0,
            'account_name' => $accountName
        ])
            ->orderBy('localbtc_released_date', 'ASC')
            ->get();
        //To verify payment fragments
        $btcAnchor = 0;

        foreach ($incomingTransactions as $incomingTransaction) {
            $remainingAmount = $incomingTransaction->remaining;

            if ($contactID !== null && isset($incomingTransaction->hold_spend[$contactID])) {
                $remainingAmount += $incomingTransaction->hold_spend[$contactID];
            }

            if ($incomingTransaction->usd_price === null) {
                //TODO: better error report, maybe contact or transaction ID.
                return false;
            }

            $btcAnchor += $remainingAmount;

            /**
             * If the BTC anchor is bigger than the amount spent
             * return TRUE because all the payer BTC fragments has USD price.
             **/
            if ($btcAnchor >= $amount) {
                return true;
            }
        }

        return false;
    }

    public function errorTransactions()
    {
        $errors = ErrorTransaction::where('was_solved', 0)->get();

        return view('transactions.errors')->with(compact('errors'));
    }

    public function getSolveError($id)
    {
        $error = ErrorTransaction::find($id);

        return view('transactions.error-form')->with(compact('error'));
    }

    public function postSolveError($id)
    {
        $inputs = request()->all();

        $error = ErrorTransaction::find($id);

        if ($inputs['error_amount'] > $inputs['amount_btc']) {
            return Redirect::back()->with(
                'error',
                'In order to solve the error BTC amount must be higher or equal than ' . $inputs['error_amount']
            );
        }

        if ($error->was_solved == 1) {
            return Redirect::back()->with('error', 'This error has already been solved.');
        }

        $released_at      = new Carbon($error->json_data['data']['released_at']);
        $transaction_date = new Carbon($inputs['transaction_date']);

        $transaction = Transaction::create([
            'bank_name'      => $inputs['origin_name'],
            'transaction_id' => $inputs['new_transaction_id'],
            'amount'         => $inputs['amount'],
            'released_date'  => $transaction_date,
            'currency'       => 'USD',
            'type'           => 'Incoming',
            'account_name'   => $error->json_data['data']['seller']['username']
        ]);

        IncomingBtc::create([
            'transaction_id' => $transaction->id,
            'amount_btc'     => $inputs['amount_btc'],
            'usd_price'      => $inputs['amount'],
            'released_date'  => $released_at->format('Y-m-d H:i:s'),
            'remaining'      => $inputs['amount_btc']
        ]);

        $error->update(['was_solved' => 1]);

        return Redirect::to('/error-transactions')->with('success', 'Error has been solved.');
    }

    public function getProfit($id)
    {
        $outgoing = OutgoingBtc::where('transaction_id', $id)->first();

        return $outgoing->profit;
    }

    /**
     * Test assets
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function aTest()
    {
        $incomingBTC = IncomingBtc::orderBy('released_date', 'desc')
            ->with(['transaction'])
            ->get();

        dd($incomingBTC);

        foreach ($incomingBTC as $item) {
            if ($item->transaction === null) {
                echo $item->id;
            }
        }
    }

    /**
     * Test assets
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function upTest()
    {
        //        $messages              = ApiHelper::getLocalBtc(1, '/api/contact_messages/38850886/');
        //        $messageWithAttachment = $messages['message_list'][2]; //Me traigo el mensaje directamente proque si, aunque debería ser dinámico.
        //        $attachmentUrl         = str_replace('https://localbitcoins.com', '', $messageWithAttachment['attachment_url']);

        //        $params = [
        //            'msg' => utf8_encode('Mensaje de texto saliente')
        //        ];
        //        $msgOut = ApiHelper::getLocalBtc(
        //            1,
        //            '/api/contact_message_post/38850886/',
        //            $params,
        //            null,
        //            'POST'
        //        );

        $params     = ['document' => public_path() . '/img/from-lcb-files/adhesive-tapes-sealants.jpg'];
        $msgFileOut = ApiHelper::getLocalBtc(
            1,
            '/api/contact_message_post/38850886/',
            $params,
            null,
            'POST'
        );

        //        var_dump($msgOut);
        var_dump($msgFileOut);
    }

    public function remainingAlerts()
    {
        $remainder = IncomingBtc::where('remaining', '>', 0)->get();

        $bitstamp = BitstampData::getNow();

        $highestPrice = 0;

        foreach ($remainder as $remain) {
            $r_price = $remain->usd_price / $remain->amount_btc;

            if ($r_price > $highestPrice) {
                $highestPrice = $r_price;
            }
        }

        if ($bitstamp <= ($highestPrice - ($highestPrice * 0.025))) {
            Pusher::trigger(
                'my-channel',
                'remaining-alert',
                [
                    'message' => 'Alerta roja, los BTCs para la venta estan mas del 2.5% por encima del precio sugerido.',
                ]
            );

            //            Mail::to([
            //                'gdf@americankryptosbank.com',
            //                'gsq@americankryptosbank.com'
            //            ])->send(new RemainingRedAlert());

            return;
        } elseif ($bitstamp <= ($highestPrice + ($highestPrice * 0.01))) {
            Pusher::trigger(
                'my-channel',
                'remaining-alert',
                [
                    'message' => 'Alerta naranja, los BTCs para la venta estan mas del 1% por encima del precio sugerido.',
                ]
            );

            //            Mail::to([
            //                'gdf@americankryptosbank.com',
            //                'gsq@americankryptosbank.com'
            //            ])->send(new RemainingOrangeAlert());

            return;
        }

        return;
    }
}
