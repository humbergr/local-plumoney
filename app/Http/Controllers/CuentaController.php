<?php

namespace App\Http\Controllers;

use App\Banco;
use App\Cuenta;
use App\Movimiento;
use Illuminate\Http\Request;

class CuentaController extends Controller
{

    public function index(Cuenta $model, Request $request, Movimiento $movimiento)
    {
        $data['model1'] = $model->orderBy('id', 'Desc')->name($request->keywords)->paginate(15);
        



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
        $data['model'] = $model->paginate(200);
if (!$request->start) {
    $data['model'] = [];
  }

        

        return view('cuentas.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('cuentas.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Cuenta $model)
    {
        $model->create($request->all());
        return redirect('cuentas')->withStatus(__('Registro creado satisfactoriamente.'));
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Cuenta $cuenta)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Cuenta $cuenta)
    {
        return view('cuentas.edit', compact('cuenta'));
    }

    /**
     * Update the specified user in storage
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Cuenta $cuenta)
    {

        $cuenta->update($request->all());

        return redirect()->route('cuentas.index')->withStatus(__('Registro ha sido actualizado.'));
    }

    /**
     * Remove the specified user from storage
     *
     * @param  \App\User
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Cuenta $cuenta)
    {
        $cuenta->delete();

        return redirect()->route('cuentas.index')->withStatus(__('Registro ha sido eliminado.'));
    }

}
