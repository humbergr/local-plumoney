<?php

namespace App\Http\Controllers;

use App\Banco;
use App\Helpers\ExcelExport;
use App\Lote;
use App\Lotedetalle;
use App\Movimiento;
use App\Role;
use App\User;
use App\UserPersonProfile;
use Carbon\Carbon;
use Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class MovimientoController extends Controller
{

    public function index(Movimiento $movimiento, Request $request)
    {



        $model = $movimiento->orderBy('id', 'Desc');


        if ($request->start != '') {
            $model->whereDate('created_at', '>=', $request->start)->whereDate('created_at', '<=', $request->end);
        }



        if ($request->currency  != '' & $request->currency != 'all') {
            $model->where('moneda', $request->currency);
        }

        if ($request->bank_id  != '' & $request->bank_id != 'all') {
            $model->where('banco_id', $request->bank_id);
            $currency = Banco::find($request->bank_id);
        }

        if ($request->numero  != '' & $request->numero != 'all') {
            $model->where('numero', $request->numero);
        }

        if ($request->cuenta_id  != '' & $request->cuenta_id != 'all') {
            $model->where('cuenta_id', $request->cuenta_id);
        }

        $datos = collect($model->get());
        $data['sum_creditos'] = $datos->where('operacion', 'INGRESO')->sum('monto');
        $data['sum_debitos'] = $datos->where('operacion', 'EGRESO')->sum('monto');;
        $data['balance'] = $datos->sum('monto');;
        $data['sum_usd'] = $model->sum('monto_usd');
        $data['currency'] = $currency->moneda ?? null;
        $data['model'] = $model->paginate(50);


        if ($request->excel) {
            $headings = ['ID',  'user_id', 'banco_id', 'cuenta_id', 'Tipo', 'Monto', 'Moneda', 'Descripción', 'Operación', 'Taza',  'MontoUSD', 'created_at', 'update_at'];
            return Excel::download(new ExcelExport($datos, $headings), Date('d-m-y') . '-movimientos.xlsx');
        }

        return view('movimientos.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function create()
    {
        return view('movimientos.create');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Movimiento $model)
    {



        $emi = explode('-', $request->emision);
        $emision = $emi[2] . '-' . $emi[0] . '-' . $emi[1];



        $input = $request->except(['monto', 'monto_usd', 'taza', 'capture', 'emision', 'comision']);

        $monto = str_replace(',', '', $request->monto);
        $monto_usd = str_replace(',', '', $request->monto_usd);
        $tasa = str_replace(',', '', $request->tasa);
        $comision = str_replace(',', '', $request->comision);


        //$monto = $montobruto +  ($comision === '' ? 0 : $comision);

        if ($request->operacion === 'EGRESO') {
            $banco = Banco::find($request->banco_id);
            if ($monto > $banco->saldo) {
                return redirect()->back()->withErrors(['monto' => 'El monto es mayor al saldo disponible'])->withInput();
            }
        }

        if (isset($request->capture)) {

            $path = Storage::disk('public')->putFile(

                'movimientos',

                $request->capture

            );

            $capture = 'storage/' . $path;
        }


        switch ($request->operacion) {
            case 'INGRESO':
                $operacion = 'sum';
                break;

            case 'EGRESO':
                $operacion = 'res';
                break;
        }




        $result = $model->create(array_merge($input, [
            'emision' => $emision,
            'comision' => ($comision == '') ? 0 : $comision,
            'capture' => $capture ?? null,
            'user_id' => Auth::id(), 'tasa' => $tasa,
            'monto_bruto' => ($operacion === 'sum') ? $monto :  $monto * -1,
            'monto' => ($operacion === 'sum') ? $monto :  $monto * -1,
            'monto_usd' => ($operacion === 'sum') ? $monto_usd :  $monto_usd * -1
        ]));


        moverSaldo($result->banco_id);



        if ($request->operacion === 'INGRESO') {
            $lote = new Lote;
            $lote->lote = $request->lote;
            $lote->tasa =  $tasa;
            $lote->banco_id = $request->banco_id;
            $lote->movimiento_id =  $result->id;
            $lote->monto = ($operacion === 'sum') ? $monto :  $monto * -1;
            $lote->currency = $request->moneda;
            $lote->saldo = 0;
            $lote->save();

            $loted = new Lotedetalle;
            $loted->lote_id =  $lote->id;
            $loted->lote = ($operacion === 'sum') ?  $request->lote :   '';
            $loted->monto =  ($operacion === 'sum') ? $monto :  $monto * -1;
            $loted->currency =  $request->moneda;
            $loted->operacion =  $request->operacion;
            $loted->tasa = $tasa;
            $loted->banco_id = $request->banco_id;
            $loted->movimiento_id =  $result->id;
            $loted->comentarios =  $request->descripcion ?? null;
            $loted->save();
        } else {


            robotEgresos($monto, $request->moneda, 'EGRESO', $tasa, $request, $result, $request->descripcion ?? null);

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

                robotEgresos($comision, $request->moneda, 'EGRESO', $tasa, $request, $result, 'Incoming Fiat Transaction');
            }
        }

        return redirect('movimientos')->withStatus(__('Registro creado satisfactoriamente.'));
    }

    /** 
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Movimiento $movimiento)
    {
        //
    }
    public function bankToBank(Movimiento $movimiento)
    {

        return view('movimientos.bank');
    }
    public function bankToBankStore(Request $request, Movimiento $model)
    {

        $request->validate([
            'banco_id' => 'required',
            'moneda' => 'required',
            'banco2_id' => 'required',
            'moneda2' => 'required',
            'monto' => 'required',
            'tasa' => 'required',
            'monto_usd' => 'required',
        ]);


        $send = $this->bankOut($request, $model);
        if ($send === false) {
            return redirect()->back()->withErrors(['monto' => 'El monto es mayor al saldo disponible'])->withInput();
        }
        $this->bankInt($request, $model);
        return redirect('movimientos')->withStatus(__('Registro creado satisfactoriamente.'));
    }


    public function bankOut($request, $model)
    {





        $input = $request->except(['monto', 'monto_usd', 'taza', 'capture', 'emision', 'comision']);

        $monto = str_replace(',', '', $request->monto);
        $monto_usd = str_replace(',', '', $request->monto_usd);
        $tasa = str_replace(',', '', $request->tasa);
        $comision = str_replace(',', '', $request->comision);

        $request->operacion = 'EGRESO';


        $montoMasComision = $monto +  ($comision === '' ? 0 : $comision);

        if ($request->operacion === 'EGRESO') {
            $banco = Banco::find($request->banco_id);
            if ($montoMasComision > $banco->saldo) {
                return false;
            }
        }


        if (isset($request->capture)) {

            $path = Storage::disk('public')->putFile(

                'movimientos',

                $request->capture

            );

            $capture = 'storage/' . $path;
        }


        /*  switch ($request->operacion) {
            case 'INGRESO':
                $operacion = 'sum';
                break;

            case 'EGRESO':
                $operacion = 'res';
                break;
        } */

        $operacion = 'res';




        $result = $model->create(array_merge($input, [
            'emision' => Carbon::now(),
            'cuenta_id' => 4,
            'lote' => uniqid(),
            'tipo' => 'TRANFERENCIA NEGATIVA',
            'operacion' => 'EGRESO',
            'comision' =>   $comision === '' ? 0 : $comision,
            'capture' => $capture ?? null,
            'user_id' => Auth::id(), 'tasa' => $tasa,
            'monto_bruto' => ($operacion === 'sum') ? $monto :  $monto * -1,
            'monto' => ($operacion === 'sum') ? $monto :  $monto * -1,
            'monto_usd' => ($operacion === 'sum') ? $monto_usd :  $monto_usd * -1
        ]));


        moverSaldo($result->banco_id);



        if ($request->operacion === 'INGRESO') {
            $lote = new Lote;
            $lote->lote = $request->lote;
            $lote->tasa =  $tasa;
            $lote->banco_id = $request->banco_id;
            $lote->movimiento_id =  $result->id;
            $lote->monto = ($operacion === 'sum') ? $monto :  $monto * -1;
            $lote->currency = $request->moneda;
            $lote->saldo = 0;
            $lote->save();

            $loted = new Lotedetalle;
            $loted->lote_id =  $lote->id;
            $loted->lote = ($operacion === 'sum') ?  $request->lote :   '';
            $loted->monto =  ($operacion === 'sum') ? $monto :  $monto * -1;
            $loted->currency =  $request->moneda;
            $loted->operacion =  $request->operacion;
            $loted->tasa = $tasa;
            $loted->banco_id = $request->banco_id;
            $loted->movimiento_id =  $result->id;
            $loted->comentarios =  $request->descripcion ?? null;
            $loted->save();
        } else {



            robotEgresos($monto, $request->moneda, 'EGRESO', $tasa, $request, $result, $request->descripcion ?? null);

            if ($comision != '' && $comision != 0) {

                $monto_usd = $comision / $tasa;
                $result = $model->create(array_merge($input, [
                    'emision' => Carbon::now(),
                    'comision' => null,
                    'capture' =>   null,
                    'user_id' => Auth::id(),
                    'tasa' => $tasa,
                    'tipo' => 'TRANFERENCIA NEGATIVA',
                    'monto_bruto' =>   $comision * -1,
                    'monto' =>  $comision * -1,
                    'cuenta_id' => 9,
                    'operacion' => 'EGRESO',
                    'monto_usd' => ($operacion === 'sum') ? $monto_usd :  $monto_usd * -1
                ]));

                robotEgresos($comision, $request->moneda, 'EGRESO', $tasa, $request, $result, 'Incoming Fiat Transaction');
            }
        }






        return true;
    }



    public function bankInt($request, $model)
    {




        $input = $request->except(['monto', 'monto_usd', 'taza', 'capture', 'emision', 'comision']);

        $monto = str_replace(',', '', $request->monto);
        $monto_usd = str_replace(',', '', $request->monto_usd);
        $tasa = str_replace(',', '', $request->tasa);
        $comision = str_replace(',', '', $request->comision);

        $request->banco_id = $request->banco2_id;
        $request->moneda = $request->moneda2;
        


        if (isset($request->capture)) {

            $path = Storage::disk('public')->putFile(

                'movimientos',

                $request->capture

            );

            $capture = 'storage/' . $path;
        }

 

        $operacion = 'sum';
        $request->operacion = 'INGRESO';

        $request->lote =  uniqid();

        $result = $model->create(array_merge($input, [
            'emision' => Carbon::now(),
            'cuenta_id' => 4,
            'lote' => $request->lote,
            'banco_id' => $request->banco2_id,
            'moneda' => $request->moneda2,
            'tipo' => 'TRANFERENCIA POSITIVA',
            'operacion' => 'INGRESO',
            'comision' => 0,
            'capture' => $capture ?? null,
            'user_id' => Auth::id(),
            'tasa' => $tasa,
            'monto_bruto' => ($operacion === 'sum') ? $monto :  $monto * -1,
            'monto' => ($operacion === 'sum') ? $monto :  $monto * -1,
            'monto_usd' => ($operacion === 'sum') ? $monto_usd :  $monto_usd * -1
        ]));



        moverSaldo($result->banco_id);



        if ($request->operacion === 'INGRESO') {
            $lote = new Lote;
            $lote->lote = $request->lote;
            $lote->tasa =  $tasa;
            $lote->banco_id = $request->banco_id;
            $lote->movimiento_id =  $result->id;
            $lote->monto = ($operacion === 'sum') ? $monto :  $monto * -1;
            $lote->currency = $request->moneda;
            $lote->saldo = 0;
            $lote->save();

            $loted = new Lotedetalle;
            $loted->lote_id =  $lote->id;
            $loted->lote = ($operacion === 'sum') ?  $request->lote :   '';
            $loted->monto =  ($operacion === 'sum') ? $monto :  $monto * -1;
            $loted->currency =  $request->moneda;
            $loted->operacion =  $request->operacion;
            $loted->tasa = $tasa;
            $loted->banco_id = $request->banco_id;
            $loted->movimiento_id =  $result->id;
            $loted->comentarios =  $request->descripcion ?? null;
            $loted->save();
        } else {


            robotEgresos($monto, $request->moneda, 'EGRESO', $tasa, $request, $result, $request->descripcion ?? null);

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

                robotEgresos($comision, $request->moneda, 'EGRESO', $tasa, $request, $result, 'Incoming Fiat Transaction');
            }
        }




        $lote = Lote::all();
        foreach ($lote as $key2 => $value2) {
            moverLote($value2->id);
        }




        return true;
    }

    public function lotes(Banco $banco)
    {

        return view('movimientos.lotes');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Movimiento $movimiento)
    {
        return view('movimientos.edit', compact('movimiento'));
    }

    /**
     * Update the specified user in storage
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Movimiento $movimiento)
    {

        $movimiento->update($request->all());

        return redirect()->route('movimientos.index')->withStatus(__('Registro ha sido actualizado.'));
    }

    /**
     * Remove the specified user from storage
     *
     * @param  \App\User
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Movimiento $movimiento)
    {
        $movimiento->delete();

        return redirect()->route('movimientos.index')->withStatus(__('Registro ha sido eliminado.'));
    }

    public function userCreate()
    {
        $roles = Role::all();
        return view('movimientos.user-create')->with(compact('roles'));;
    }
    public function userCreateStore(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'currency' => 'required',

        ]);
        $verification_token = str_random(24);

        $exc = $request->except(['password']);

        $user = User::create(array_merge($exc, ['role_id' => 5, 'verification_token' => $verification_token, 'is_verified' => 0, 'password' => Hash::make($request->password)]));

        $profile = UserPersonProfile::create(['user_id' => $user->id, 'email' => $request->email]);

        return redirect()->back()->withStatus('Usuario creado')->withInput();
    }
}
