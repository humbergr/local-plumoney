<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Mail;
use App\Mail\TransactionsErrormonthlysage;
use App\Mail\RemainingOrangeAlert;
use App\Mail\RemainingRedAlert;
use Pusher\Laravel\Facades\Pusher;

use App\UserWalletsTransactions;
use App\Tier;
use App\TierFile;
use App\UserPersonProfile;
use App\TierLevel;

use App\Transaction;
use App\CurrencyWallet;
use App\LocalWallet;
use App\LocalTrades;
use GuzzleHttp\Client;
use Carbon\Carbon;
use App\BitstampData;
use App\IncomingBtc;
use App\OutgoingBtc;
use Auth;
use DB;

/**
     * type
     *
     * 1 = recarga de billetera
     * 2 = operaciones de envio
     * 4 = Operaciones Entre Billetera
     * 5 = operaciones de recepcion
     */

class AmlBsa extends Controller
{
    public function operacionesDeEnvio(){
        $type = 2;
        return view('aml-bsa.operations')->with(compact('type', 'tiers'));
    }

    public function getData(Request $request){

        config()->set('database.connections.mysql.strict', false);
        \DB::reconnect();

        if ($request->type == 2) {
            //Envio
            $transactions = UserWalletsTransactions::where('type', '=', 2)
                                                ->select('user_id', 'amount', 'tier_review', 'currency')
                                                ->selectRaw("(
                                                    SELECT SUM( amount ) 
                                                    FROM  user_wallets_transactions AS table1
                                                    WHERE table1.user_id =  user_wallets_transactions.user_id
                                                          AND type = 2
                                                          AND created_at >= '$request->year-01-01 00:00:00'
                                                          AND created_at <= '$request->year-12-31 23:59:59'
                                                  ) AS year,
                                                  (
                                                    SELECT SUM( amount ) 
                                                    FROM  user_wallets_transactions AS table1
                                                    WHERE table1.user_id =  user_wallets_transactions.user_id
                                                          AND type = 2
                                                          AND created_at >= '$request->trimesterStar-01 00:00:00'
                                                          AND created_at <= '$request->month-31 23:59:59'
                                                  ) AS trimester,
                                                  (
                                                    SELECT SUM( amount ) 
                                                    FROM  user_wallets_transactions AS table1
                                                    WHERE table1.user_id =  user_wallets_transactions.user_id
                                                          AND type = 2
                                                          AND created_at >= '$request->month-01 00:00:00'
                                                          AND created_at <= '$request->month-31 23:59:59'
                                                  ) AS month,
                                                  (
                                                    SELECT SUM( amount ) 
                                                    FROM  user_wallets_transactions AS table1
                                                    WHERE table1.user_id =  user_wallets_transactions.user_id
                                                          AND type = 2
                                                          AND created_at >= '$request->lastDayOfWeek 00:00:00'
                                                          AND created_at <= '$request->dayDate 23:59:59'
                                                  ) AS week,
                                                  (
                                                    SELECT SUM( amount ) 
                                                    FROM  user_wallets_transactions AS table1
                                                    WHERE table1.user_id =  user_wallets_transactions.user_id
                                                          AND type = 2
                                                          AND created_at >= '$request->dayDate 00:00:00'
                                                          AND created_at <= '$request->dayDate 23:59:59'
                                                  ) AS day")
                                                ->whereRaw("(
                                                    SELECT SUM( amount ) 
                                                    FROM  user_wallets_transactions AS table1
                                                    WHERE table1.user_id =  user_wallets_transactions.user_id
                                                          AND type = 2
                                                          AND created_at >= '$request->dayDate 00:00:00'
                                                          AND created_at <= '$request->dayDate 23:59:59'
                                                  ) > 0")
                                                ->with('userAccount', 'userPersonProfile')
                                                ->groupBy('user_id');
        
        
        }elseif ($request->type == 1) {
            // Recarga de Billeteras
            $transactions = UserWalletsTransactions::where('type', '=', 1)
                                                ->select('user_id', 'amount', 'tier_review', 'currency')
                                                ->selectRaw("(
                                                    SELECT SUM( amount ) 
                                                    FROM  user_wallets_transactions AS table1
                                                    WHERE table1.user_id =  user_wallets_transactions.user_id
                                                          AND type = 1
                                                          AND created_at >= '$request->year-01-01 00:00:00'
                                                          AND created_at <= '$request->year-12-31 23:59:59'
                                                  ) AS year,
                                                  (
                                                        SELECT SUM( amount ) 
                                                        FROM  user_wallets_transactions AS table1
                                                        WHERE table1.user_id =  user_wallets_transactions.user_id
                                                            AND type = 1
                                                            AND created_at >= '$request->trimesterStar-01 00:00:00'
                                                            AND created_at <= '$request->month-31 23:59:59'
                                                    ) AS trimester,
                                                  (
                                                    SELECT SUM( amount ) 
                                                    FROM  user_wallets_transactions AS table1
                                                    WHERE table1.user_id =  user_wallets_transactions.user_id
                                                          AND type = 1
                                                          AND created_at >= '$request->month-01 00:00:00'
                                                          AND created_at <= '$request->month-31 23:59:59'
                                                  ) AS month,
                                                  (
                                                    SELECT SUM( amount ) 
                                                    FROM  user_wallets_transactions AS table1
                                                    WHERE table1.user_id =  user_wallets_transactions.user_id
                                                          AND type = 1
                                                          AND created_at >= '$request->lastDayOfWeek 00:00:00'
                                                          AND created_at <= '$request->dayDate 23:59:59'
                                                  ) AS week,
                                                  (
                                                    SELECT SUM( amount ) 
                                                    FROM  user_wallets_transactions AS table1
                                                    WHERE table1.user_id =  user_wallets_transactions.user_id
                                                          AND type = 1
                                                          AND created_at >= '$request->dayDate 00:00:00'
                                                          AND created_at <= '$request->dayDate 23:59:59'
                                                  ) AS day")
                                                ->whereRaw("(
                                                    SELECT SUM( amount ) 
                                                    FROM  user_wallets_transactions AS table1
                                                    WHERE table1.user_id =  user_wallets_transactions.user_id
                                                          AND type = 1
                                                          AND created_at >= '$request->dayDate 00:00:00'
                                                          AND created_at <= '$request->dayDate 23:59:59'
                                                  ) > 0")
                                                ->with('userAccount', 'userPersonProfile')
                                                ->groupBy('user_id');
            
        }elseif ($request->type == 4) {
            // Entre billeteras
            $transactions = UserWalletsTransactions::where('type', '=', 4)
                                                    ->select('user_id', 'amount', 'tier_review', 'currency', 'metadata')
                                                    ->selectRaw("(
                                                        SELECT SUM( amount ) 
                                                        FROM  user_wallets_transactions AS table1
                                                        WHERE table1.user_id =  user_wallets_transactions.user_id
                                                            AND type = 4
                                                            AND created_at >= '$request->year-01-01 00:00:00'
                                                            AND created_at <= '$request->year-12-31 23:59:59'
                                                    ) AS year,
                                                    (
                                                        SELECT SUM( amount ) 
                                                        FROM  user_wallets_transactions AS table1
                                                        WHERE table1.user_id =  user_wallets_transactions.user_id
                                                            AND type = 4
                                                            AND created_at >= '$request->trimesterStar-01 00:00:00'
                                                            AND created_at <= '$request->month-31 23:59:59'
                                                    ) AS trimester,
                                                    (
                                                        SELECT SUM( amount ) 
                                                        FROM  user_wallets_transactions AS table1
                                                        WHERE table1.user_id =  user_wallets_transactions.user_id
                                                            AND type = 4
                                                            AND created_at >= '$request->month-01 00:00:00'
                                                            AND created_at <= '$request->month-31 23:59:59'
                                                    ) AS month,
                                                    (
                                                        SELECT SUM( amount ) 
                                                        FROM  user_wallets_transactions AS table1
                                                        WHERE table1.user_id =  user_wallets_transactions.user_id
                                                            AND type = 4
                                                            AND created_at >= '$request->lastDayOfWeek 00:00:00'
                                                            AND created_at <= '$request->dayDate 23:59:59'
                                                    ) AS week,
                                                    (
                                                        SELECT SUM( amount ) 
                                                        FROM  user_wallets_transactions AS table1
                                                        WHERE table1.user_id =  user_wallets_transactions.user_id
                                                            AND type = 4
                                                            AND created_at >= '$request->dayDate 00:00:00'
                                                            AND created_at <= '$request->dayDate 23:59:59'
                                                    ) AS day")
                                                    ->whereRaw("(
                                                        SELECT SUM( amount ) 
                                                        FROM  user_wallets_transactions AS table1
                                                        WHERE table1.user_id =  user_wallets_transactions.user_id
                                                            AND type = 4
                                                            AND created_at >= '$request->dayDate 00:00:00'
                                                            AND created_at <= '$request->dayDate 23:59:59'
                                                    ) > 0")
                                                    ->with('userAccount', 'userPersonProfile')
                                                    ->groupBy('user_id');
                                                    
        
        }elseif ($request->type == 5) {
            # code...
            $transactions = UserWalletsTransactions::where('purpose', '=', 1)
                                                    ->where('type', '=', 4)
                                                ->select('user_id', 'amount', 'tier_review', 'currency')
                                                ->selectRaw("(
                                                    SELECT SUM( amount ) 
                                                    FROM  user_wallets_transactions AS table1
                                                    WHERE table1.user_id =  user_wallets_transactions.user_id
                                                          AND type = 4
                                                          AND purpose = 1
                                                          AND created_at >= '$request->year-01-01 00:00:00'
                                                          AND created_at <= '$request->year-12-31 23:59:59'
                                                  ) AS year,
                                                  (
                                                    SELECT SUM( amount ) 
                                                    FROM  user_wallets_transactions AS table1
                                                    WHERE table1.user_id =  user_wallets_transactions.user_id
                                                        AND type = 4
                                                        AND purpose = 1
                                                        AND created_at >= '$request->trimesterStar-01 00:00:00'
                                                        AND created_at <= '$request->month-31 23:59:59'
                                                  ) AS trimester,
                                                  (
                                                    SELECT SUM( amount ) 
                                                    FROM  user_wallets_transactions AS table1
                                                    WHERE table1.user_id =  user_wallets_transactions.user_id
                                                          AND type = 4
                                                          AND purpose = 1
                                                          AND created_at >= '$request->month-01 00:00:00'
                                                          AND created_at <= '$request->month-31 23:59:59'
                                                  ) AS month,
                                                  (
                                                    SELECT SUM( amount ) 
                                                    FROM  user_wallets_transactions AS table1
                                                    WHERE table1.user_id =  user_wallets_transactions.user_id
                                                          AND type = 4
                                                          AND purpose = 1
                                                          AND created_at >= '$request->lastDayOfWeek 00:00:00'
                                                          AND created_at <= '$request->dayDate 23:59:59'
                                                  ) AS week,
                                                  (
                                                    SELECT SUM( amount ) 
                                                    FROM  user_wallets_transactions AS table1
                                                    WHERE table1.user_id =  user_wallets_transactions.user_id
                                                          AND type = 4
                                                          AND purpose = 1
                                                          AND created_at >= '$request->dayDate 00:00:00'
                                                          AND created_at <= '$request->dayDate 23:59:59'
                                                  ) AS day")
                                                ->whereRaw("(
                                                    SELECT SUM( amount ) 
                                                    FROM  user_wallets_transactions AS table1
                                                    WHERE table1.user_id =  user_wallets_transactions.user_id
                                                          AND type = 4
                                                          AND purpose = 1
                                                          AND created_at >= '$request->dayDate 00:00:00'
                                                          AND created_at <= '$request->dayDate 23:59:59'
                                                  ) > 0")
                                                ->with('userAccount', 'userPersonProfile')
                                                ->groupBy('user_id');
        }

        if (isset($request->sort)) {
            # code...
            $transactions = $transactions->orderBy($request->sort, $request->order);
        }

        if (isset($request->userName)) {
            $userName = $request->userName;

            if ($userName) {

                $transactions = $transactions->whereHas(
                                                'userAccount',
                                                function ($query) use ($userName) {
                                                    $query->where('users.name',
                                                                'LIKE',
                                                                '%' . $userName . '%');
                                                }
                                            );
            }
        }

        if (isset($request->userLastname)) {

            $userLastname = $request->userLastname;

            if ($userLastname) {

                $transactions = $transactions->whereHas(
                                                'userAccount',
                                                function ($query) use ($userLastname) {
                                                    $query->where('users.name',
                                                                'LIKE',
                                                                '%' . $userLastname . '%');
                                                }
                                            );
            }
        }

        if (isset($request->userEmail)) {

            $userEmail = $request->userEmail;

            if ($userEmail) {
                $transactions = $transactions->whereHas(
                                                'userAccount',
                                                function ($query) use ($userEmail) {
                                                    $query->where('users.email',
                                                                'LIKE',
                                                                '%' . $userEmail . '%');
                                                }
                                            );
            }
        }

        $transactions = $transactions->paginate(15);
        
        config()->set('database.connections.mysql.strict', true);
        \DB::reconnect();

        return json_encode($transactions);
    }

    public function operacionesDeRecepcion(){

        $type = 5;
        return view('aml-bsa.operations')->with(compact('type', 'tiers'));
    }

    public function recargaDeBilletera(){

        $type = 1;
        return view('aml-bsa.operations')->with(compact('type', 'tiers'));
    }

    public function operacionesEntreBilletera(){

        $type = 4;
        return view('aml-bsa.operations')->with(compact('type', 'tiers'));
    }

    public function compraBtc(){
        $transactions = Transaction::where('type', 'incoming')->with(['incomingBtc'])
                                    ->orderBy('released_date', 'desc')
                                    ->paginate(16);

        $type = 6;
        
        return view('aml-bsa.operations-btc')->with(compact('transactions', 'type'));
    }

    public function ventaBTC(){
        $transactions = Transaction::where('type', 'Outgoing')->with(['outgoingBtc'])
                                        ->orderBy('released_date', 'desc')
                                        ->paginate(16);

        $type = 7;
        
        return view('aml-bsa.operations-btc')->with(compact('transactions', 'type'));
    }

    /**
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function pagination(): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $inputs      = request();
        $currentPage = $inputs->page;

        Paginator::currentPageResolver(function () use ($currentPage) {
            return $currentPage;
        });

        if($inputs->type == 1){
            $transactions = UserWalletsTransactions::where([
                'type' => 1
            ])
                ->select('user_id')
                ->orderBy('id', 'DESC')
                ->with('userAccount')
                ->with('destinationAccount')
                ->groupBy('user_id');
        }

        if($inputs->type == 2){
            $transactions = UserWalletsTransactions::where([
                'type' => 2
            ])
                ->select('user_id')
                ->orderBy('id', 'DESC')
                ->with('userAccount')
                ->groupBy('user_id');
        }

        if($inputs->type == 4){
            $transactions = UserWalletsTransactions::where([
                'type'      => 4,
                'purpose'   => 1,
            ])
                ->select('user_id')
                ->orderBy('id', 'DESC')
                ->with('userAccount')
                ->with('destinationAccount')
                ->groupBy('user_id');
        }

        if($inputs->type == 5){
            $transactions = UserWalletsTransactions::where([
                'purpose'          => 2
            ])
                ->select('user_id')
                ->orderBy('id', 'DESC')
                ->with('userAccount')
                ->with('destinationAccount')
                ->groupBy('user_id');
        }

        if($inputs->type == 6){
            $transactions = Transaction::where('type', 'incoming')->with(['incomingBtc'])
                                    ->orderBy('released_date', 'desc');
        }

        if($inputs->type == 7){
            $transactions = Transaction::where('type', 'Outgoing')->with(['outgoingBtc'])
                                        ->orderBy('released_date', 'desc');
        }

        return $transactions->orderBy('id', 'DESC')->paginate(15);
    }

    public function sentRevision(Request $request){


        if($request->tier_review == true || $request->tier_review == 1){


            if(isset($request->transaction)){

                $transactions              = UserWalletsTransactions::findOrFail($request->transaction);
                $transactions->tier_review = $request->tier_review;
    
                $userProfile                  = UserPersonProfile::where('user_id', $transactions->user_id)->first();
                if ($userProfile->tier_level < 4) {
                    $userProfile->approval_status = 4;
                    $userProfile->in_review_tier  = 1;
                }

                $tier            = Tier::where('transaction_id', $transactions->id)->first();
    
                if(is_object($tier)){
    
                    $tier->in_review = true;
                    
                    $tier->save();
                    $transactions->save();
                    $userProfile->save();
    
                }else{
                    
                    $tierNew                     = new Tier();
                    $tierNew->user_id            = $transactions->user_id;
                    $tierNew->tier_id            = $request->tier_id;
                    $tierNew->transaction_id     = $transactions->id;
                    $tierNew->type_transaction   = $transactions->type;
                    $tierNew->traking_id         = $transactions->tracking_id;
                    $tierNew->in_review          = true;
                    
                    $tierNew->save();
                    $transactions->save();
                    $userProfile->save();
                }
                
            }else{
                $userProfile                  = UserPersonProfile::where('user_id', $request->user)->first();
                if ($userProfile->tier_level < 4) {
                    $userProfile->approval_status = 4;
                    $userProfile->in_review_tier  = 1;
                    
                    $userProfile->save();
                }
            }
            
        }else{

            if(isset($request->transaction)){

                $transactions              = UserWalletsTransactions::findOrFail($request->transaction);
                $transactions->tier_review = $request->tier_review;

                $userProfile                  = UserPersonProfile::where('user_id', $transactions->user_id)->first();
                $userProfile->approval_status = 2;
                $userProfile->in_review_tier  = 0;
                $tier            = Tier::where('transaction_id', $transactions->id)->first();
                $tier->in_review = false;
                $tier->save();
                $transactions->save();
                $userProfile->save();
            
            }else{

                $userProfile                  = UserPersonProfile::where('user_id', $request->user)->first();
                if ($userProfile->tier_level < 4) {
                    $userProfile->approval_status = 2;
                    $userProfile->in_review_tier  = 0;
                    $userProfile->save();
                }
            }

        }
    
    }

    public function sentUserRevision(Request $request){


            if(isset($request->user)){
 
    
                $userProfile                  = UserPersonProfile::where('user_id', $user)->first();
                if ($userProfile->tier_level < 4) {
                    $userProfile->approval_status = 4;
                    $userProfile->in_review_tier  = 1;
                }

        
                    
                $tierNew                     = new Tier();
                $tierNew->user_id            = $userProfile->user_id;
                $tierNew->tier_id            = $request->tier_id;
                $tierNew->in_review          = true;
                
                $tierNew->save();
                $transactions->save();
                $userProfile->save();
            
                
            }
            
        
    }


    public function uploadTierDocs(Request $request, $id){
        $tierFile             = new TierFile;
        $inputs               = request()->all();
        $tierFile->user_id    = $id;
        $tierFile->tier_id    = $inputs['tier_id'];
        echo 'hooola';
        print_r($inputs['tier_doc']);
        $i = 0;
        foreach ($inputs['tier_doc'] as $req => $val) {
            # code...
            echo ' clavearrriba: ' . $val[$req];
            foreach ($req as $key => $value) {
                # code...
                echo $req[$key] . ' clave: ' . $key . ' ' .$value->getClientOriginalName();
            }
        }
        // $files = "";
        // if ($request->hasfile('tier_doc')) {
        //     foreach ($request->file('tier_doc') as $file) {
        //         $name = strtolower(str_replace(
        //                                ' ',
        //                                '',
        //                                $file->getClientOriginalName()
        //                            ));

        //         $name = preg_replace('/[^A-Za-z0-9 _ .-]/', '', $name);

        //         $file->move(
        //             base_path() . '/public/img/requeriments_tier/' . $id . '/' . '/',
        //             $name
        //         );

        //         $routePath = '/img/requeriments_tier/' . $id . '/' . $name;
        //         $files     .= $routePath . " , ";
        //     }
        // }
        // $tierFile->requeriments_tier = $files;

        // $tierFile->save();

        // return Redirect::back()->with('success', 'Muchas gracias por enviar los dumentos solicitados, seguiremos en contacto contigo');

    }

    public function unblockUser($id)
    {
        $userProfile                  = UserPersonProfile::where('user_id', $id)->first();
        $tier                         = Tier::where(['user_id' => $id, 'in_review' => 1])->first();

        if ($userProfile->tier_level < 4) {
            $userProfile->approval_status = 2;
            $userProfile->in_review_tier  = 0;
            $userProfile->tier_level = $userProfile->tier_level + 1;

            $tier->in_review = false;

            $userProfile->save();
            $tier->save();

            return Redirect::back()->with('success', 'usuario Desbloqueado');;
        }
       
    }

    public function getDataToReview(Request $request){
        $inputs       = request()->all();
        $array        = json_decode($inputs['transaction'], true);

        if($inputs['type'] == 1){

            $trans     = UserWalletsTransactions::where([
                                        'user_id' => $array['user_account']['id'],
                                        'type' => 1
                                    ]);

        }

        if($inputs['type'] == 2){
  
            $trans     = UserWalletsTransactions::where([
                                        'user_id' => $array['user_account']['id'],
                                        'type' => 2
                                    ]);
        }

        if($inputs['type'] == 4){

            $trans     = UserWalletsTransactions::where([
                                    'user_id' => $array['user_account']['id'],
                                    'type' => 4
                                ]);

        }

        if($inputs['type'] == 5){

            $trans     = UserWalletsTransactions::where([
                                    'user_id' => $array['user_account']['id'],
                                    'purpose' => 1,
                                    'type'    => 4
                                ]);

        }

        if (isset($inputs['traking'])) {

                $trans = $trans->where('tracking_id', '=', $inputs['traking']);
            
        }
        
        $trans = $trans->get();

        return json_encode($trans);
    }

    public function getTiers()
    {
        # code...
        $tiers = TierLevel::all();
        return json_encode($tiers);
    }

    public function tierSettings(Request $request){
        
        return view('aml-bsa.tier-setting')->with(compact('tiers'));
    }

    public function newTierLevel(Request $request)
    {
        $tier_level            = new TierLevel();

        if (isset($request->requirements)) {

            $jsonArray = json_decode($request->requirements,true);
            $requirements = '';
            
            foreach ($jsonArray as $aja) {
                $requirements .= $aja['requirement'] . " , ";
            }
            
        }

        $tier_level->name         = $request->name;
        $tier_level->requirements = $requirements;
        $tier_level->save();
        $tiers = TierLevel::all();
        return json_encode($tiers);
    }

    public function UpdateTierLevel(Request $request){
        if(isset($request->id)){
            $tier = TierLevel::findorfail($request->id);
            if (is_object($tier)) {

                if (isset($request->requirements)) {

                    $jsonArray = json_decode($request->requirements,true);
                    $requirements = '';
        
                    var_dump($jsonArray);
                    
                    foreach ($jsonArray as $aja) {
                        $requirements .= $aja['requirement'] . " , ";
                    }
                    
                }

                $tier->name = $request->name;
                $tier->requirements = $requirements;
                $tier->save();
                return $tier;
            }
        }
        $tiers = TierLevel::all();
        return json_encode($tiers);
    }

    public function deteleTierLevel(Request $request)
    {
        # code...
        if (isset($request->id)) {
            
            $tier = TierLevel::find($request->id);

            $tier->delete();
            $tiers = TierLevel::all();
            return json_encode($tiers);
        }
    }

    public function test(){
        return view('mvp-home');
    }
}


// Al filtrar por dia solo se tomara en cuanta unicamente los usuarios que realizaron operaciones ese dia, tomando ✅
// sus montos respectivos (Diario, semana, mensual, anual) ✅

//Filtro de fecha usar uno que muestre por dia, dia anterior, mes, mes anterior, anual, rango ✅
//si se selecciona un rango de fecha se tomara datos de cuanto proceso en ese rango ✅

// mostrar en la lista que dia, que semana, que mes y que año se estan obteniendo los datos ✅

//DETALLES
// Aparecer una tabla ✅
// personas a quienes les envio de manera de mauyor a menor o como este en el filtro
// nombre y apellido a quien le envio, monto que le envio durante el dia, mes, año
// al darle click al usuario saldra l tracking del envio, fecha del envio y monto del envio
// colocar checkmark para cada operacion entre tracking (se maracara como sospechoso el que envia y el qye recibe) para justificar los datos de los montos

//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

//traducir al ingles!!
// cambiar fecha a la primera fecha tomada ✅
// agregar filtro trimestre ✅

// filtros en el modal de view details ✅

//fecha (DD-MM-YYY) -- Tranking (_blank a la informacion de la operacion) -- Tipo via de la operacion (de bs adolares de cuanto se entro y cuando se cargo y si es zelle igual), -- direcciones IP de donde se hizo la recarga (agregar ip) ✅

// Boton de marcar sospechoso al lado de nombre y apellido ✅ (emitir red flag ¿quiere marcar cliente como red flag?) 

// Al marcar como sospechosa deberia salir una confirmación de si esta seguro que desea marcar la operación ✅
// Mostrar el nombre del usuario logeado ¿Rafael esta seguro que desea marcar la operación? ✅
// Enviar un correo cuando se marque el nivel de tier con todo lo que debe cargar

// Mostrar que nivel de tier en la tabla tier (barrita de carga) ✅

// Crear pestañas por tier en el perfil ✅
// Configuración de TIER (se agregaran los requerimientos, tipos de tier y los campos necesraio, agregar un campo de comentarios) ✅
// En configuración TIER se creara niveles de tier, se agregaran a solicitar documentos necesarios! ✅
//
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// Operaciones en revision para customer transaction (si supera un limite de los establecido la operacion no se mostrara en /exchange-transactions-list sino que ira a AML/confirmar-operaciones para una posible aprobacion o revisión)
// CREAR un boton donde muestre un historial de ip (con sus filtros respectivos similar a los demas)
// 
// DEJAR bien planteado lo de las ip para el siguiente proyecto!
// 
// RECEPTION
// Manejar con usuriaros que no estan registrado, sera quien envio dinero desde AKB hasta un cuenta
// Se bloquearan los numero de cuenta, correo, numero de telefono!
// Mostrar mayor a menor por cada rango de fecha
// Mostrar cuanto ha RECIBIDO y si se puede de quien


//recargar componentes

// Cargar datos de la persona que le envia deinero al usuario en transferencua entre wallet, tomar todos sus operaciones entre wallet ✅

/**
 * 1) NOMBRE DEL REQUERIMIENTO --------------------> Cargar documento
 *                                            Nombre de los documentos cargados
 * 
 * al cargar los cumentos cambiar el mensaje del modal por uno que diga "NOMBRE, muchas gracias por completar el envio de todos tus documentos 
 * espera nuesta pronta respuesta" cambiar signo por un check
 * 
 * Rechazar por documento o por Tier completo ademas de poder dejarle comentarios al usuario 
 * Agrupar documentos por pregunta
 * 
 * Imagenes mas pequeñás y que se muestren en un modal tipo el de la foto de perfil
 * Agregar un cuadro de texto donde se pueda completar la informacion entre cliente y oficial relacionado a los documentos cargados
 * 
 * ----
 * Entre billeteras
 * Agrupar por envios y receptions
 * Ordenar por IP y por correos
 * Hacer linkeable el correo que te lleve al perfil de ese usuario
 * 
 * --
 * REceptions
 * Usuarios que reciben dinero atraves de la plataforma 
 * mostrar quien le envio dinero
 * si es sospechoso se bloqueara Documento de identidad, correo y numero de cuenta
 */