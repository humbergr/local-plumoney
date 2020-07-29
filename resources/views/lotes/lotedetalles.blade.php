@extends('layouts.coinbank-layout-sin-sidebar')


@section('css')
{{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/country-select-js/2.0.1/css/countrySelect.min.css" integrity="sha256-nEi3429617679e6HunQ6KpCztvItMxIOkEW5u88qSdM=" crossorigin="anonymous" />


<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.12/css/select2.min.css" integrity="sha256-FdatTf20PQr/rWg+cAKfl6j4/IY3oohFAJ7gVC3M34E=" crossorigin="anonymous" />

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css" integrity="sha256-nbyata2PJRjImhByQzik2ot6gSHSU4Cqdz5bNYL2zcU=" crossorigin="anonymous" />

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.4.6/css/flag-icon.min.css" integrity="sha256-YjcCvXkdRVOucibC9I4mBS41lXPrWfqY2BnpskhZPnw=" crossorigin="anonymous" />


 --}}
@endsection
@section('content')


<div class="container">


    @if (session('status'))

    <div class="alert alert-success alert-dismissible fade show" role="alert">

        {{ session('status') }}

        <button type="button" class="close" data-dismiss="alert" aria-label="Close">

            <span aria-hidden="true">&times;</span>

        </button>

    </div>

    @endif
    @foreach ($errors->all() as $element)

    <span class="text-danger"> {{ $element }}</span>

    @endforeach





    <div class="card mt-3">

        <div class="card-header text-primary font-weight-bold">
            <h4>Batch Transaction Adds</h4>
        </div>

        <table class="table  ">

            <thead class="text-default">

            </thead>
            <tbody>

                @foreach ($lote as $element2)
                @php
                $moneda = $element2->currency;
                $init_balance = $element2->monto / $element2->tasa;
                $init_bal = $element2->monto ;
                @endphp

                <tr>
                    <th nowrap>Date: {{$element2->created_at->format('m/d/Y')}}
                        <div class="mt-4">

                            <div>From dive Funds: </div>
                            <div> {{$element2->movimiento->user->name}}</div>
                            <div class="mt-3">Feés bank:  </div>
                            <div class="text-danger">{{currency($fees,$element2->currency)}} <span class="ml-2 mr-2 text-black-50 font-weight-bold h4">~</span> {{currency($feesusd,'$USD')}}</div>


                    </th>


                    <th>Batch Number: <a target="_blank"
                            href="{{ url('/movimientos/'.$element2->movimiento_id.'/edit/') }}">{{$element2->lote}}</a>
                    </th>
                    <th>Bank Account Name: {{$element2->banco->name}} <span class="text-muted">
                            ({{$element2->banco->numero}})</span></th>
                    <th nowrap>Exchange Rate: <br><span class="text-muted">
                            {{currency($element2->tasa,$element2->currency)}}</span></th>

                    <th nowrap>Initial Balance:
                        <div> {{currency($element2->monto,$element2->currency)}} </div>
                        <div class=" text-right text-primary"> {{currency($element2->monto / $element2->tasa,'USD$')}}
                        </div>
                    </th>
                    <th nowrap>Balance:
                        <div>{{currency($element2->saldo,$element2->currency)}}</div>
                        <div
                            class=" text-right text-primary"> {{currency($element2->saldo / $element2->tasa,'USD$')}}
                        </div>
                    </th>


                </tr>





                @endforeach

            </tbody>
        </table>


        <div>

        </div>

        <div class="d-flex justify-content-between">
            <div></div>
            <div></div>


            <div class="text-primary text-right h3 font-weight-bold mt-3 mr-3">
                {{ currency(  ($element2->saldo / $element2->tasa - ($element2->monto / $element2->tasa)),'USD$')  }}
                <div style="margin-top: -10px"><small>Balance Processed</small></div>
            </div>

        </div>

        <hr>
        <div class="card-body">


            <table class="table table-striped">

                <thead class="text-primary">
                    <tr>
                        <th>Date</th>

                        <th>Trancking Number </th>

                        <th>Rate</th>
                        <th>Credit</th>
                        <th>Debit</th>
                        <th>Balance</th>
                        <th>USD Incoming</th>

                        <th></th>
                    </tr>


                </thead>
                <tbody>

                    @php
                    $dolares=0;
                    $summonto =0;
                    $summonto2 =0;
                    $val =0;
                    $count = $model;



                    @endphp
                    @foreach ($model as $key => $element)





                    <tr>
                        <td>{{$element['model']->created_at->format('m/d/Y')}}</td>

                        @if ( $element['model']->movimiento->descripcion === 'Customer Transaction' &&
                        $element['model']->movimiento->doc_id != '' )
                        <td><a target="_blank"
                                href="{{ url('exchange-transaction/'.$element['model']->movimiento->doc_id , []) }}">{{ $element['model']->movimiento->doc['tracking_id'] }}
                                <br><small>{{ $element['model']->movimiento->descripcion }}<small </a> </td> @elseif (
                                        $element['model']->movimiento->descripcion === 'Incoming Fiat Transaction' &&
                                        $element['model']->movimiento->doc_id != '' )
                        <td><a target="_blank"
                                href="{{ url('transactions/edit/'.$element['model']->movimiento->doc_id , []) }}">{{ $element['model']->movimiento->inc['transaction_id']}}
                                <br><small>{{ $element['model']->movimiento->descripcion }}<small </a> </td> @else
                                        <td><a target="_blank"
                                            href="{{ url('/movimientos/'.$element['model']->movimiento_id.'/edit/') }}">
                                            {{ $element['model']->movimiento_id }}<br><small>Movement<small> </a> </td>



                        @endif




                        <td>{{currency($element['model']->tasa,$moneda)}}</td>


                        @if ($element['model']->monto > 0)
                        <td class="{{($element['model']->monto > 0) ? 'text-success' : 'text-danger'}}">
                            {{currency($element['model']->monto,$moneda)}}


                        </td>
                        @else
                        <td></td>
                        @endif


                        @if ($element['model']->monto < 0) <td
                            class="{{($element['model']->monto > 0) ? 'text-success' : 'text-danger'}}">
                            {{currency($element['model']->monto,$moneda)}}

                            @if ($element['model']->partial)
                            <i title="Partial" class="text-primary fa fa-exclamation-triangle"></i>
                            @endif

                            </td>
                            @else
                            <td></td>
                            @endif

                            @php
                            $summonto += $element['model']->monto ;
                            @endphp


                            <td class="text-primary"> {{   currency ($summonto,$moneda)   }} </td>







                            <td class="text-primary">


                                @if ($element['model']->operacion === 'EGRESO' &&
                                $element['model']->movimiento->cuenta_id !== 9)

                                {{ currency( ($element['model']->monto *-1) / $element['model']->tasa,'USD$') }}
                                @php
                                $dolares += ($element['model']->monto *-1) / $element['model']->tasa;
                                @endphp
                                @endif

                                @if ($element['model']->movimiento->cuenta_id === 9)
                                <div class="text-danger">
                                    {{ currency( ($element['model']->monto *-1) / $element['model']->tasa,'USD$') }}
                                </div>
                                <div class="badge badge-primary text-center"> Feés Bank </div>
                                @endif
                            </td>
                    </tr>
                    @endforeach

                </tbody>
                <tbody>

                    <tr class="text-primary font-weight-bold">


                        <td></td>
                        <td></td>

                        <td>Total</td>
                        <td>{{currency($credits,$moneda)}}</td>
                        <td class="text-danger">{{currency($debits,$moneda)}}</td>
                        <td></td>
                        <td> {{currency($dolares,'USD$')}} </td>

                    </tr>


                    <tr class="text-primary font-weight-bold">


                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="text-right">Balance</td>
                        <td>{{currency($balance,$moneda)}}</td>
                        <td class=" font-weight-bold"> {{currency($element2->saldo / $element2->tasa,'USD$')}}</td>

                    </tr>




                </tbody>
            </table>
            @if (round($dolares- $init_balance) == 0)

            <div class="text-center  font-weight-bold h3 text-primary ">
                {{  currency(  $dolares- $init_balance ,'USD$')}}</div>
            @else

            <div
                class="text-center  font-weight-bold h3 {{  ( round($dolares- $init_balance) > 0 ) ? 'text-success' : 'text-danger'}}  ">
                {{  currency(  $dolares- $init_balance ,'USD$')}}</div>
            @endif
            <div class="text-center text-primary font-weight-bold"> Profit Margin Obtained</div>


        </div> {{--card-body  --}}
    </div> {{--card  --}}





</div>


@endsection

@section('js')
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/country-select-js/2.0.1/js/countrySelect.min.js" integrity="sha256-CJtHxCZRQpS4Q4X7X4T8i/PcJC3ZKT0rnQ25bX4yM5Y=" crossorigin="anonymous"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.12/js/select2.full.min.js" integrity="sha256-vucLmrjdfi9YwjGY/3CQ7HnccFSS/XRS1M/3k/FDXJw=" crossorigin="anonymous"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/16.0.11/js/intlTelInput.min.js" integrity="sha256-679hprK8vxlf4fnVBENMDhjXffz6MSULSiah9G9FRZg=" crossorigin="anonymous"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/16.0.11/js/utils.js" integrity="sha256-Dgk4ywhuqU0wvPuVIPRY9AtcRW0G7YaGT/MCLDAVDNE=" crossorigin="anonymous"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/16.0.11/js/intlTelInput-jquery.min.js" integrity="sha256-uQ9YDP/OGOHLO5qgqlVq1nRTM7OJEAee5mryVZLlJVg=" crossorigin="anonymous"></script>

  --}}
@endsection