<?php

namespace App\Http\Controllers;

use App\Banco;
use App\Lote;
use Illuminate\Http\Request;

class BancoController extends Controller
{

    public function index(Banco $model, Request $request)
    {

        $lote = Lote::all();
        foreach ($lote as $key2 => $value2) {
            moverLote($value2->id);
        }

        foreach ($model->get() as $key => $value) {
            moverSaldo($value->id);
        }


       /*  if ($request->start != '') {
            dd(1);
            $model->whereDate('created_at', '>=', $request->start)->whereDate('created_at', '<=', $request->end);
        } */
           $model = $model->orderBy('id', 'Desc');

        if ($request->pais  != '' ) {
            
            $model->where('pais', $request->pais);
        }
        if ($request->currency  != '' && $request->currency != 'all') {
           
            $model->where('moneda', $request->currency);
        }

        if ($request->bank_id  != '' && $request->bank_id != 'all') {
           
            $model->where('id', $request->bank_id);
            
        }
 
        $total_usd=0;
        foreach ($model->get() as $key => $value) {
            $total_usd += $value->usdSaldo();
        }
        $data['total'] = $model->sum('saldo');;
        $data['total_usd'] = $total_usd ;;
        $data['currency'] = $model->first();
     
         
        $data['model'] = $model->paginate(50);



        //   $model = $model->orderBy('id', 'Desc')->name($request->keywords)->paginate(50);
        //  dd();

        return view('bancos.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('bancos.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Banco $model)
    {

        $input = $request->except(['comision']);

        $comision = str_replace(',', '', $request->comision);

        $request->validate([
            'pais'         => 'required',
            'cajaobanco'   => 'required',
            'name'         => 'required',
            'numero'       => 'required',
            'tipo'         => 'required',
            'beneficiario' => 'required',
            'moneda'       => 'required',

        ]);

        $model->create(array_merge($input, ['comision' => $comision]));
        return redirect('bancos')->withStatus(__('Registro creado satisfactoriamente.'));
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Banco $banco)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Banco $banco)
    {
        return view('bancos.edit', compact('banco'));
    }

    /**
     * Update the specified user in storage
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Banco $banco)
    {

        $input = $request->except(['comision']);

        $comision = str_replace(',', '', $request->comision);

        $banco->update(array_merge($input, ['comision' => $comision]));

        return redirect()->back()->withStatus(__('Registro ha sido actualizado.'));
    }

    /**
     * Remove the specified user from storage
     *
     * @param  \App\User
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Banco $banco)
    {
        $banco->delete();

        return redirect()->route('bancos.index')->withStatus(__('Registro ha sido eliminado.'));
    }
}
