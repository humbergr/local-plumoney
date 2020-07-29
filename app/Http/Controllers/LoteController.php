<?php

namespace App\Http\Controllers;

use App\Lote;
use App\Lotedetalle;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class LoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Lote $model, Request $request)
    {
        $model = $model->orderBy('id', 'Desc')->name($request->keywords)->paginate(50);


        return view('lotes.index', ['model' => $model]);
    }
    public function lotedetalles(Lotedetalle $model,   $id)
    {


        $model = $model->where('lote_id', $id)->get();
        $entrada = $model->where('lote_id', $id)->last();

        $credits = $model->where('lote_id', $id)->where('operacion', 'INGRESO')->sum('monto');
        $debits = $model->where('lote_id', $id)->where('operacion', 'EGRESO')->sum('monto');
        $balance = $credits + $debits;

        $array = array();
        $sum = 0;
        $fees=0;
        $feesusd=0;
        $cuenta = 0;
        foreach ($model as $key => $value) {
            if ( $value->movimiento->cuenta_id === 9 ) {
          //   dd($value);

                $pre[] = [$value->monto];
                $fees += $value->monto;
                $feesusd += $value->monto / $value->tasa;
            }
            $array[] = ['model' => $value, 'analisis' => round($entrada->tasa / $value->tasa * 100, 2)];
            $sum += round($entrada->tasa / $value->tasa * 100, 2);
            $cuenta++;
        }

        //dd($pre);

        $collection = Collection::make($array);

        $porcentaje =  $sum / $cuenta;

        $monto = ($entrada->monto *  $porcentaje / 100)  -  $entrada->monto;


        // dd( $monto, $entrada->monto, $porcentaje );
        return view('lotes.lotedetalles', [
            'credits' => $credits,
            'debits' => $debits,
            'balance' => $balance,
            'lote' => Lote::where('id', $id)->get(),
            'model' =>   $collection,
            'entrada' => $entrada,
            'porcentaje' =>  $porcentaje,
            'monto' =>  $monto,
            'fees' =>  $fees,
            'feesusd' =>  $feesusd
        ]);
    }


    public function lotedetallesBanco(Lote $model, Request $request,  $id)
    {




        $model = $model->where('banco_id', $id)->orderBy('id', 'Desc')->name($request->keywords);
        if ($request->start != '') {
            $model->whereDate('created_at', '>=', $request->start)->whereDate('created_at', '<=', $request->end);
        }
        $sum = 0;
        $deb = 0;
        $cre = 0;
        foreach ($model->get() as $key => $value) {
            $profit = $value->profit();

            if ($value->profit() > 0) {
                $cre += $profit;
            } else {
                $deb += $profit;
            }
            $sum += $profit;
        }
        return view('lotes.index', [
            'model' => $model->paginate(50),
            'id' => $id,
            'cre' => $cre,
            'deb' => $deb,
            'sum' => $sum

        ]);
    }

    public function lotedetallesCancel(Lote $lote)
    {
        $lote->active = false;
        $lote->update();

        $loteDetalle = Lotedetalle::where('lote_id', $lote->id)->get();

        if ($loteDetalle != null) {

            foreach ($loteDetalle as $item) {
                $loteDetalle2 = Lotedetalle::find($item->id);
                $loteDetalle2->active = false;
                $loteDetalle2->update();
            }
        }

        return redirect()->back()->withStatus(__('Void Batch'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Lote  $lote
     * @return \Illuminate\Http\Response
     */
    public function show(Lote $lote)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Lote  $lote
     * @return \Illuminate\Http\Response
     */
    public function edit(Lote $lote)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Lote  $lote
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Lote $lote)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Lote  $lote
     * @return \Illuminate\Http\Response
     */
    public function destroy(Lote $lote)
    {
        //
    }

    public function transaction(Request $request, LoteDetalle $loteDetalle)
    {
        $model = $loteDetalle->orderBy('id', 'Desc');


        if ($request->start != '') {
            $model->whereDate('created_at', '>=', $request->start)->whereDate('created_at', '<=', $request->end);;
        }



        if ($request->currency  != '' & $request->currency != 'all') {
            $model->where('currency', $request->currency);
        }

        if ($request->banco_id  != '' & $request->banco_id != 'all') {
            $model->where('banco_id', $request->banco_id);
        }



        $data['model'] =  $model->paginate(50);
        return view('lotes.transaction', $data);
    }
}
